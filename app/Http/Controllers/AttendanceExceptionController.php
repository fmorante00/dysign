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

        // Only offer events currently accepting live manual check-ins.
        // Keep the legacy event list unchanged until its database is migrated.
        if (\Illuminate\Support\Facades\Schema::hasColumn('attendance_exceptions', 'checked_out_at')) {
            $now = Carbon::now(self::TZ);

            $events = $events->filter(function (Event $event) use ($now) {
                [$startsAt, $endsAt] = $this->eventWindow($event);

                return $now->gte($startsAt->copy()->subHour())
                    && $now->lte($endsAt)
                    && !in_array(strtolower((string) $event->status), ['cancelled', 'canceled'], true);
            })->values();
        }
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

        // The database determines the capture mode, never the browser.
        if (\Illuminate\Support\Facades\Schema::hasColumn('attendance_exceptions', 'checked_out_at')) {
            $request->merge(['capture_mode' => 'live']);
        } else {
            $request->request->remove('capture_mode');
        }

        $data = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,event_id'],
            'student_number' => ['required', 'string', 'max:100'],
            'issue_type' => ['required', Rule::in(['Missed Scan', 'Unreadable RFID', 'Scanner Failure', 'RFID Not Registered'])],
            'observed_at' => ['required_unless:capture_mode,live', 'nullable', 'date'],
            'capture_mode' => ['sometimes', 'in:live'],
            'reason' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $event = Event::query()->findOrFail($data['event_id']);
        abort_unless(
            $event->assignments()->where('personnel_id', $personnelId)->exists(),
            403,
            'You are not assigned to this event.'
        );

        $event->syncScheduledStatus();
        $observedAt = ($data['capture_mode'] ?? null) === 'live'
            ? Carbon::now(self::TZ)
            : Carbon::parse($data['observed_at'], self::TZ);
        $this->validateObservedAt($event, $observedAt);

        $student = Student::query()
            ->where('student_number', trim($data['student_number']))
            ->where('status', 'Active')
            ->first();

        if (!$student) {
            throw ValidationException::withMessages(['student_number' => 'Active student not found.']);
        }

        if (!$this->isExpectedParticipant((int) $event->event_id, (int) $student->student_id)) {
            throw ValidationException::withMessages([
                'student_number' => 'Student must be Required or actively registered for this Optional event.',
            ]);
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

    /** Capture a real manual checkout timestamp without staff-entered times. */
    public function personnelCheckOut(Request $request, AttendanceException $attendanceException): RedirectResponse
    {
        $personnelId = $request->user()->personnel?->personnel_id;
        abort_unless($personnelId, 403);

        if (!\Illuminate\Support\Facades\Schema::hasColumn('attendance_exceptions', 'checked_out_at')) {
            abort(503, 'Manual checkout is not enabled for this database.');
        }

        DB::transaction(function () use ($request, $attendanceException, $personnelId) {
            $exception = AttendanceException::whereKey($attendanceException->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $event = Event::findOrFail($exception->event_id);

            abort_unless(
                (int) $exception->reported_by === (int) $request->user()->user_id
                && $event->assignments()->where('personnel_id', $personnelId)->exists(),
                403
            );

            if ($exception->status !== 'Pending' || $exception->checked_out_at !== null) {
                throw ValidationException::withMessages([
                    'checkout' => 'This exception is already checked out or reviewed.',
                ]);
            }

            if (AttendanceRecord::where('event_id', $exception->event_id)
                ->where('student_id', $exception->student_id)->exists()) {
                throw ValidationException::withMessages([
                    'checkout' => 'An attendance record already exists for this student and event.',
                ]);
            }

            $timeIn = Carbon::parse(
                $exception->observed_at->format('Y-m-d H:i:s'),
                self::TZ
            );

            $this->validateObservedAt($event, $timeIn);

            $timeOut = Carbon::now(self::TZ);
            [, $endsAt] = $this->eventWindow($event);

            if ($timeOut->lte($timeIn)
                || $timeOut->lt($endsAt)
                || $timeOut->gt($endsAt->copy()->addHour())) {
                throw ValidationException::withMessages([
                    'checkout' => 'Checkout is allowed only from event end until one hour afterward (PH time).',
                ]);
            }

            $exception->update([
                'checked_out_at' => $timeOut->format('Y-m-d H:i:s'),
                'checked_out_by' => $request->user()->user_id,
            ]);
        });

        return back()->with('success', 'Manual checkout recorded. Awaiting Department Staff approval.');
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

        $automaticCheckoutEnabled = \Illuminate\Support\Facades\Schema::hasColumn(
            'attendance_exceptions',
            'checked_out_at'
        );

        $data = $request->validate([
            'decision' => ['required', Rule::in(['Approved', 'Rejected'])],
            'verified_time_out' => $automaticCheckoutEnabled
                ? ['nullable', 'date']
                : ['required_if:decision,Approved', 'nullable', 'date'],
            'review_notes' => $automaticCheckoutEnabled
                ? ['required_if:decision,Rejected', 'nullable', 'string', 'max:2000']
                : [
                    'required', 'string', 'max:2000',
                    ...($request->input('decision') === 'Approved' ? ['min:10'] : []),
                ],
        ]);

        DB::transaction(function () use ($attendanceException, $departmentId, $data, $request, $automaticCheckoutEnabled) {
            $exception = AttendanceException::whereKey($attendanceException->getKey())->lockForUpdate()->firstOrFail();
            $event = Event::whereKey($exception->event_id)->firstOrFail();
            abort_unless((int) $event->department_id === (int) $departmentId, 403);

            if ($exception->status !== 'Pending') {
                throw ValidationException::withMessages(['decision' => 'This request has already been reviewed.']);
            }

            $attendanceId = null;

            if ($data['decision'] === 'Approved') {
                $event->syncScheduledStatus();
                // Eloquent may interpret stored DATETIME values in app timezone;
                // treat the previously recorded local observation as a PH wall-clock time.
                $observedAt = Carbon::parse(
                    $exception->observed_at->format('Y-m-d H:i:s'),
                    self::TZ
                );
                $this->validateObservedAt($event, $observedAt);

                if ($automaticCheckoutEnabled) {
                    if (!$exception->checked_out_at || !$exception->checked_out_by) {
                        throw ValidationException::withMessages([
                            'decision' => 'Personnel must complete the actual Manual Check-Out before staff approval.',
                        ]);
                    }

                    $verifiedTimeOut = Carbon::parse(
                        $exception->checked_out_at->format('Y-m-d H:i:s'),
                        self::TZ
                    );
                } else {
                    $verifiedTimeOut = Carbon::parse($data['verified_time_out'], self::TZ);
                }

                $this->validateVerifiedTimeOut($event, $observedAt, $verifiedTimeOut);

                $student = Student::where('student_id', $exception->student_id)->lockForUpdate()->firstOrFail();
                if ($student->status !== 'Active') {
                    throw ValidationException::withMessages(['decision' => 'The student is no longer active.']);
                }
                if (!$this->isExpectedParticipant((int) $event->event_id, (int) $student->student_id)) {
                    throw ValidationException::withMessages([
                        'decision' => 'Student must be Required or actively registered for this Optional event.',
                    ]);
                }
                if (AttendanceRecord::where('event_id', $event->event_id)->where('student_id', $student->student_id)->exists()) {
                    throw ValidationException::withMessages(['decision' => 'An RFID/manual attendance record already exists. Reject this request if it is no longer needed.']);
                }

                [$startsAt] = $this->eventWindow($event);
                $finalStatus = $observedAt->greaterThan($startsAt) ? 'Late' : 'Present';

                // A clearly identified manual record, not a fabricated RFID scan.
                // The approving staff member is recorded on attendance_exceptions.reviewed_by.
                $attendance = AttendanceRecord::create([
                    'event_id' => $event->event_id,
                    'student_id' => $student->student_id,
                    'rfid_identifier' => 'MANUAL-EXCEPTION-' . $exception->exception_id,
                    'time_in' => $observedAt,
                    'time_out' => $verifiedTimeOut,
                    'status' => $finalStatus,
                    'scanned_by' => null,
                ]);
                $attendanceId = $attendance->attendance_id;
            }

            $exception->update([
                'status' => $data['decision'],
                'reviewed_by' => $request->user()->user_id,
                'reviewed_at' => now(),
                'review_notes' => trim($data['review_notes'] ?? '')
                    ?: ($automaticCheckoutEnabled && $data['decision'] === 'Approved'
                        ? 'Reviewed and approved using personnel-captured check-in and check-out times.'
                        : null),
                'attendance_id' => $attendanceId,
            ]);
        });

        return back()->with('success', 'Exception reviewed. ' . ($data['decision'] === 'Approved' ? 'Manual attendance was recorded.' : 'Request was rejected.'));
    }

    /**
     * A personnel observation is evidence of Time In, not an RFID scan.
     * Time In is valid from 1 hour before event start through scheduled event end.
     */
    private function validateObservedAt(Event $event, Carbon $observedAt): void
    {
        if (strtolower((string) $event->status) === 'cancelled' || strtolower((string) $event->status) === 'canceled') {
            throw ValidationException::withMessages([
                'observed_at' => 'Cancelled events cannot be manually verified.',
            ]);
        }

        [$startsAt, $endsAt] = $this->eventWindow($event);
        $checkInOpens = $startsAt->copy()->subHour();

        if ($observedAt->lt($checkInOpens)
            || $observedAt->gt($endsAt)
            || $observedAt->gt(Carbon::now(self::TZ))) {
            throw ValidationException::withMessages([
                'observed_at' => 'Verified Time In must be between 1 hour before event start and event end, and not in the future (PH time).',
            ]);
        }
    }

    /** Staff must verify Time Out within event end through 1 hour after end. */
    private function validateVerifiedTimeOut(Event $event, Carbon $timeIn, Carbon $timeOut): void
    {
        [, $endsAt] = $this->eventWindow($event);
        $checkOutCloses = $endsAt->copy()->addHour();

        if ($timeOut->lte($timeIn)
            || $timeOut->lt($endsAt)
            || $timeOut->gt($checkOutCloses)
            || $timeOut->gt(Carbon::now(self::TZ))) {
            throw ValidationException::withMessages([
                'verified_time_out' => 'Verified Time Out must be after Time In, between event end and 1 hour after event end, and not in the future (PH time).',
            ]);
        }
    }

    /** @return array{0: Carbon, 1: Carbon} Scheduled event start/end (supports overnight events). */
    private function eventWindow(Event $event): array
    {
        $date = Carbon::parse($event->event_date, self::TZ)->format('Y-m-d');
        $startTime = Carbon::parse($event->start_time, self::TZ)->format('H:i:s');
        $endTime = Carbon::parse($event->end_time, self::TZ)->format('H:i:s');

        $startsAt = Carbon::parse("{$date} {$startTime}", self::TZ);
        $endsAt = Carbon::parse("{$date} {$endTime}", self::TZ);
        if ($endsAt->lessThan($startsAt)) {
            $endsAt->addDay();
        }

        return [$startsAt, $endsAt];
    }

    /** Expected = Required, or Optional with active registration. */
    private function isExpectedParticipant(int $eventId, int $studentId): bool
    {
        $eligibility = EventEligibleStudent::query()
            ->where('event_id', $eventId)
            ->where('student_id', $studentId)
            ->first();

        if (!$eligibility) {
            return false;
        }

        if ($eligibility->participation_type === 'Required') {
            return true;
        }

        if ($eligibility->participation_type !== 'Optional') {
            return false;
        }

        return DB::table('event_registrations')
            ->where('event_id', $eventId)
            ->where('student_id', $studentId)
            ->where('status', 'Registered')
            ->exists();
    }
}
