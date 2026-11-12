<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\EventEligibleStudent;
use App\Models\EventRegistration;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EventRegistrationController extends Controller
{
    /** Student: list only events for which the student is eligible. */
    public function studentIndex(): View
    {
        $student = $this->currentStudent();

        Event::syncScheduledStatuses();

        $events = Event::with(['department'])
            ->whereHas('eligibleStudents', function ($query) use ($student) {
                $query->where('student_id', $student->student_id);
            })
            ->where('status', '!=', 'Cancelled')
            ->orderByDesc('event_date')
            ->orderBy('start_time')
            ->get();

        $registrations = EventRegistration::query()
            ->where('student_id', $student->student_id)
            ->whereIn('event_id', $events->pluck('event_id'))
            ->get()
            ->keyBy('event_id');

        $eligibilities = EventEligibleStudent::query()
            ->where('student_id', $student->student_id)
            ->whereIn('event_id', $events->pluck('event_id'))
            ->get()
            ->keyBy('event_id');

        return view('student.registrations.index', compact(
            'student', 'events', 'registrations', 'eligibilities'
        ));
    }

    /** Student: register for an eligible upcoming event. */
    public function studentStore(int $event): RedirectResponse
    {
        $student = $this->currentStudent();

        $this->registerStudent($event, $student, 'Student');

        return redirect()->route('student.registrations.index')
            ->with('success', 'You have successfully registered for the event.');
    }

    /** Student: cancel their own registration before the event starts. */
    public function studentCancel(int $event): RedirectResponse
    {
        $student = $this->currentStudent();

        DB::transaction(function () use ($event, $student) {
            $lockedEvent = Event::query()->where('event_id', $event)
                ->lockForUpdate()->firstOrFail();

            $this->ensureRegistrationOpen($lockedEvent);
            $this->cancelRegistration($lockedEvent, $student->student_id);
        });

        return redirect()->route('student.registrations.index')
            ->with('success', 'Your event registration was cancelled.');
    }

    /** Staff: show the events organized by their own department. */
    public function staffIndex(): View
    {
        $departmentId = $this->staffDepartmentId();

        Event::syncScheduledStatuses();

        $events = Event::with('department')
            ->withCount('eligibleStudents')
            ->where('department_id', $departmentId)
            ->orderByDesc('event_date')
            ->orderBy('start_time')
            ->get();

        $counts = EventRegistration::query()
            ->select('event_id')
            ->selectRaw('COUNT(*) AS registration_count')
            ->where('status', 'Registered')
            ->whereIn('event_id', $events->pluck('event_id'))
            ->groupBy('event_id')
            ->pluck('registration_count', 'event_id');

        return view('events.registration-events', compact('events', 'counts'));
    }

    /** Staff: view registered participants for one authorized event. */
    public function staffShow(Request $request, int $event): View
    {
        $managedEvent = $this->staffEvent($event);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($filters['search'] ?? '');

        $participants = EventRegistration::query()
            ->with(['student', 'registeredBy'])
            ->where('event_id', $managedEvent->event_id)
            ->where('status', 'Registered')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where(function ($q) use ($search) {
                        $q->where('student_number', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
                });
            })
            ->orderByDesc('registered_at')
            ->paginate(20)
            ->withQueryString();

        $registeredCount = EventRegistration::where('event_id', $managedEvent->event_id)
            ->where('status', 'Registered')
            ->count();
        $eligibleCount = EventEligibleStudent::where('event_id', $managedEvent->event_id)
            ->count();
        $canEdit = $managedEvent->calculateScheduledStatus() === 'Upcoming';

        return view('events.registrations', compact(
            'managedEvent', 'participants', 'registeredCount', 'eligibleCount', 'canEdit', 'search'
        ));
    }

    /** Staff: register an eligible student using their student number. */
    public function staffStore(Request $request, int $event): RedirectResponse
    {
        $this->staffEvent($event);

        $validated = $request->validate([
            'student_number' => ['required', 'string', 'max:100'],
        ]);

        $student = Student::where('student_number', trim($validated['student_number']))
            ->where('status', 'Active')->first();

        if (!$student) {
            throw ValidationException::withMessages([
                'student_number' => 'No active student was found for that student number.',
            ]);
        }

        $this->registerStudent($event, $student, 'Department Staff');

        return redirect()->route('events.registrations.show', $event)
            ->with('success', 'Student successfully added to the participant list.');
    }

    /** Staff: remove a registration before the event starts. */
    public function staffCancel(int $event, int $registration): RedirectResponse
    {
        $this->staffEvent($event);

        DB::transaction(function () use ($event, $registration) {
            $lockedEvent = Event::query()->where('event_id', $event)
                ->where('department_id', $this->staffDepartmentId())
                ->lockForUpdate()->firstOrFail();

            $this->ensureRegistrationOpen($lockedEvent);

            $record = EventRegistration::query()
                ->where('event_id', $lockedEvent->event_id)
                ->where('registration_id', $registration)
                ->where('status', 'Registered')
                ->firstOrFail();

            $this->cancelRegistration($lockedEvent, $record->student_id);
        });

        return redirect()->route('events.registrations.show', $event)
            ->with('success', 'Participant registration cancelled.');
    }

    private function registerStudent(int $eventId, Student $student, string $source): void
    {
        DB::transaction(function () use ($eventId, $student, $source) {
            // Lock the event to serialize competing registrations for this event.
            $event = Event::query()->where('event_id', $eventId)
                ->lockForUpdate()->firstOrFail();

            if ($source === 'Department Staff' && (int) $event->department_id !== $this->staffDepartmentId()) {
                abort(403, 'This event is outside your department.');
            }

            $this->ensureRegistrationOpen($event);

            $isEligible = EventEligibleStudent::query()
                ->where('event_id', $event->event_id)
                ->where('student_id', $student->student_id)
                ->exists();

            if ($student->status !== 'Active' || !$isEligible) {
                throw ValidationException::withMessages([
                    'registration' => 'The selected student is not eligible for this event.',
                ]);
            }

            $registration = EventRegistration::firstOrNew([
                'event_id' => $event->event_id,
                'student_id' => $student->student_id,
            ]);

            if ($registration->exists && $registration->status === 'Registered') {
                throw ValidationException::withMessages([
                    'registration' => 'This student is already registered for the event.',
                ]);
            }

            $registration->fill([
                'status' => 'Registered',
                'source' => $source,
                'registered_by' => auth()->id(),
                'registered_at' => now(),
                'cancelled_at' => null,
            ]);
            $registration->save();
        });
    }

    private function cancelRegistration(Event $event, int $studentId): void
    {
        // Do not erase a registration after attendance has been recorded.
        if (AttendanceRecord::query()
            ->where('event_id', $event->event_id)
            ->where('student_id', $studentId)
            ->exists()) {
            throw ValidationException::withMessages([
                'registration' => 'This registration cannot be cancelled because attendance is already recorded.',
            ]);
        }

        $registration = EventRegistration::query()
            ->where('event_id', $event->event_id)
            ->where('student_id', $studentId)
            ->where('status', 'Registered')
            ->first();

        if (!$registration) {
            throw ValidationException::withMessages([
                'registration' => 'No active registration exists for this student and event.',
            ]);
        }

        $registration->update([
            'status' => 'Cancelled',
            'cancelled_at' => now(),
        ]);
    }

    private function ensureRegistrationOpen(Event $event): void
    {
        if ($event->calculateScheduledStatus() !== 'Upcoming') {
            throw ValidationException::withMessages([
                'registration' => 'Registration is only available before an upcoming event starts.',
            ]);
        }
    }

    private function currentStudent(): Student
    {
        $user = auth()->user();
        if (!$user || !$user->student_id) {
            abort(403, 'Your account is not linked to a student record.');
        }

        $student = Student::findOrFail($user->student_id);
        if ($student->status !== 'Active') {
            abort(403, 'Your student record is inactive.');
        }

        return $student;
    }

    private function staffDepartmentId(): int
    {
        $user = auth()->user();
        if (!$user || $user->role?->role_name !== 'Department Staff' || !$user->department_id) {
            abort(403, 'A Department Staff account with a department is required.');
        }

        return (int) $user->department_id;
    }

    private function staffEvent(int $eventId): Event
    {
        return Event::with('department')
            ->where('event_id', $eventId)
            ->where('department_id', $this->staffDepartmentId())
            ->firstOrFail();
    }
}
