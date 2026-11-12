<?php

namespace App\Http\Controllers;

use App\Models\AttendanceException;
use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\EventEligibleStudent;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AttendanceExceptionController extends Controller
{
    private const TZ = 'Asia/Manila';

    /** Attendance Personnel: assigned events and own reported exceptions. */
    public function personnelIndex(): View
    {
        $personnelId = auth()->user()->personnel?->personnel_id;
        abort_unless($personnelId, 403, 'Attendance Personnel account has no personnel record.');

        Event::syncScheduledStatuses();

        $events = Event::query()
            ->whereHas('assignments', fn ($q) => $q->where('personnel_id', $personnelId))
            ->where('status', '!=', 'Cancelled')
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->get();

        $exceptions = AttendanceException::with(['event', 'student', 'reviewer'])
            ->where('reported_by', auth()->id())
            ->orderByDesc('exception_id')
            ->paginate(15);

        return view('personnel.attendance-exceptions.index', compact('events', 'exceptions'));
    }

    /** Report a missed/failed scan; it NEVER creates attendance automatically. */
    public function personnelStore(Request $request): RedirectResponse
    {
        $personnelId = $request->user()->personnel?->personnel_id;
        abort_unless($personnelId, 403, 'Attendance Personnel account has no personnel record.');

        $data = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,event_id'],
            'student_number' => ['required', 'string', 'max:100'],
            'issue_type' => ['required', Rule::in(['Missed Scan', 'Unreadable RFID', 'Scanner Failure', 'RFID Not Registered'])],
            'observed_at' => ['required', 'date'],
            'reason' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $event = Event::query()->findOrFail($data['event_id']);
        abort_unless(
            $event->assignments()->where('personnel_id', $personnelId)->exists(),
            403,
            'You are not assigned to this event.'
        );

        $event->syncScheduledStatus();
        $observedAt = Carbon::parse($data['observed_at'], self::TZ);
        $this->validateObservedAt($event, $observedAt);

        $student = Student::query()
            ->where('student_number', trim($data['student_number']))
            ->where('status', 'Active')
            ->first();

        if (!$student) {
            throw ValidationException::withMessages(['student_number' => 'Active student not found.']);
        }

        if (!EventEligibleStudent::where('event_id', $event->event_id)->where('student_id', $student->student_id)->exists()) {
            throw ValidationException::withMessages(['student_number' => 'This student is not on the event eligibility list.']);
        }

        // Lock the student so two concurrent personnel requests cannot create duplicate pending claims.
        DB::transaction(function () use ($event, $student, $data, $observedAt, $request) {
            Student::where('student_id', $student->student_id)->lockForUpdate()->firstOrFail();

            if (AttendanceRecord::where('event_id', $event->event_id)->where('student_id', $student->student_id)->exists()) {
                throw ValidationException::withMessages(['student_number' => 'This student already has an attendance record for the event.']);
            }

            if (AttendanceException::where('event_id', $event->event_id)
                ->where('student_id', $student->student_id)
                ->where('status', 'Pending')->exists()) {
                throw ValidationException::withMessages(['student_number' => 'A pending exception already exists for this student and event.']);
            }

            AttendanceException::create([
                'event_id' => $event->event_id,
                'student_id' => $student->student_id,
                'reported_by' => $request->user()->user_id,
                'issue_type' => $data['issue_type'],
                'observed_at' => $observedAt,
                'reason' => trim($data['reason']),
                'status' => 'Pending',
            ]);
        });

        return back()->with('success', 'Exception submitted. Department Staff must review it before attendance is recorded.');
    }

    /** Department Staff: only requests for events owned by their own department. */
    public function staffIndex(Request $request): View
    {
        $departmentId = $request->user()->department_id;
        abort_unless($departmentId, 403, 'Staff account is not assigned to a department.');

        $data = $request->validate([
            'event_id' => ['nullable', 'integer'],
            'status' => ['nullable', Rule::in(['Pending', 'Approved', 'Rejected'])],
        ]);

        $events = Event::where('department_id', $departmentId)
            ->orderByDesc('event_date')->get(['event_id', 'event_name', 'event_date']);

        $exceptions = AttendanceException::with(['event', 'student', 'reporter', 'reviewer', 'attendance'])
            ->whereHas('event', fn ($q) => $q->where('department_id', $departmentId))
            ->when(!empty($data['event_id']), fn ($q) => $q->where('event_id', $data['event_id']))
            ->when(!empty($data['status']), fn ($q) => $q->where('status', $data['status']))
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
            ->orderByDesc('exception_id')
            ->paginate(15)
            ->withQueryString();

        $pendingCount = AttendanceException::where('status', 'Pending')
            ->whereHas('event', fn ($q) => $q->where('department_id', $departmentId))
            ->count();

        return view('staff.attendance-exceptions.index', compact('events', 'exceptions', 'pendingCount'));
    }

    /** Approving creates one traceable manual attendance entry, never overwrites RFID scans. */
    public function staffReview(Request $request, AttendanceException $attendanceException): RedirectResponse
    {
        $departmentId = $request->user()->department_id;
        abort_unless($departmentId, 403, 'Staff account is not assigned to a department.');

        $data = $request->validate([
            'decision' => ['required', Rule::in(['Approved', 'Rejected'])],
            'attendance_status' => ['required_if:decision,Approved', Rule::in(['Present', 'Late'])],
            'review_notes' => ['required_if:decision,Rejected', 'nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($attendanceException, $departmentId, $data, $request) {
            $exception = AttendanceException::whereKey($attendanceException->getKey())->lockForUpdate()->firstOrFail();
            $event = Event::whereKey($exception->event_id)->firstOrFail();
            abort_unless((int) $event->department_id === (int) $departmentId, 403);

            if ($exception->status !== 'Pending') {
                throw ValidationException::withMessages(['decision' => 'This request has already been reviewed.']);
            }

            $attendanceId = null;

            if ($data['decision'] === 'Approved') {
                $event->syncScheduledStatus();
                $this->validateObservedAt($event, $exception->observed_at);

                $student = Student::where('student_id', $exception->student_id)->lockForUpdate()->firstOrFail();
                if ($student->status !== 'Active') {
                    throw ValidationException::withMessages(['decision' => 'The student is no longer active.']);
                }
                if (!EventEligibleStudent::where('event_id', $event->event_id)->where('student_id', $student->student_id)->exists()) {
                    throw ValidationException::withMessages(['decision' => 'Student is no longer eligible for this event.']);
                }
                if (AttendanceRecord::where('event_id', $event->event_id)->where('student_id', $student->student_id)->exists()) {
                    throw ValidationException::withMessages(['decision' => 'An RFID/manual attendance record already exists. Reject this request if it is no longer needed.']);
                }

                $attendance = AttendanceRecord::create([
                    'event_id' => $event->event_id,
                    'student_id' => $student->student_id,
                    'rfid_identifier' => 'MANUAL-EXCEPTION-' . $exception->exception_id,
                    'time_in' => $exception->observed_at,
                    'status' => $data['attendance_status'],
                    'scanned_by' => $request->user()->user_id,
                ]);
                $attendanceId = $attendance->attendance_id;
            }

            $exception->update([
                'status' => $data['decision'],
                'reviewed_by' => $request->user()->user_id,
                'reviewed_at' => now(),
                'review_notes' => trim($data['review_notes'] ?? '') ?: null,
                'attendance_id' => $attendanceId,
            ]);
        });

        return back()->with('success', 'Exception reviewed. ' . ($data['decision'] === 'Approved' ? 'Manual attendance was recorded.' : 'Request was rejected.'));
    }

    /** The observation must be within the event schedule and cannot be in the future. */
    private function validateObservedAt(Event $event, Carbon $observedAt): void
    {
        if ($event->status === 'Cancelled') {
            throw ValidationException::withMessages(['observed_at' => 'Cancelled events cannot be manually verified.']);
        }

        $date = Carbon::parse($event->event_date)->format('Y-m-d');
        $start = Carbon::parse($date . ' ' . $event->start_time, self::TZ);
        $end = Carbon::parse($date . ' ' . $event->end_time, self::TZ);

        if ($observedAt->lt($start) || $observedAt->gt($end) || $observedAt->gt(Carbon::now(self::TZ))) {
            throw ValidationException::withMessages([
                'observed_at' => 'Observation time must be during the event schedule and must not be in the future.',
            ]);
        }
    }
}
