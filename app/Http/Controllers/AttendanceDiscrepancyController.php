<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDiscrepancy;
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

class AttendanceDiscrepancyController extends Controller
{
    private const TZ = 'Asia/Manila';

    /** Students can only submit and see their own event attendance discrepancies. */
    public function studentIndex(Request $request): View
    {
        $studentId = $request->user()->student_id;
        abort_unless($studentId, 403, 'No student record is linked to this account.');

        Event::syncScheduledStatuses();
        $events = Event::query()
            ->whereHas('eligibleStudents', fn ($q) => $q->where('student_id', $studentId))
            ->where('status', 'Completed')
            ->orderByDesc('event_date')
            ->get();

        $attendanceByEvent = AttendanceRecord::where('student_id', $studentId)
            ->whereIn('event_id', $events->pluck('event_id'))
            ->get()
            ->keyBy('event_id');

        $discrepancies = AttendanceDiscrepancy::with(['event', 'reviewer', 'verifier'])
            ->where('student_id', $studentId)
            ->orderByDesc('discrepancy_id')
            ->paginate(10);

        return view('student.attendance-discrepancies.index', compact('events', 'attendanceByEvent', 'discrepancies'));
    }

    public function studentStore(Request $request): RedirectResponse
    {
        $studentId = $request->user()->student_id;
        abort_unless($studentId, 403, 'No student record is linked to this account.');

        $data = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,event_id'],
            'request_type' => ['required', Rule::in(['Missing Attendance', 'Incorrect Status', 'Incorrect Time'])],
            'requested_status' => ['nullable', Rule::in(['Present', 'Late'])],
            'requested_time_in' => ['nullable', 'date'],
            'reason' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $event = Event::findOrFail($data['event_id']);
        $event->syncScheduledStatus();
        if ($event->status !== 'Completed') {
            throw ValidationException::withMessages(['event_id' => 'You can only dispute completed events.']);
        }
        if (!EventEligibleStudent::where('event_id', $event->event_id)->where('student_id', $studentId)->exists()) {
            throw ValidationException::withMessages(['event_id' => 'You are not eligible for this event.']);
        }

        $type = $data['request_type'];
        $needsStatus = in_array($type, ['Missing Attendance', 'Incorrect Status'], true);
        $needsTime = in_array($type, ['Missing Attendance', 'Incorrect Time'], true);
        if ($needsStatus && empty($data['requested_status'])) {
            throw ValidationException::withMessages(['requested_status' => 'Select the requested attendance status.']);
        }
        if ($needsTime && empty($data['requested_time_in'])) {
            throw ValidationException::withMessages(['requested_time_in' => 'Provide the requested attendance time.']);
        }
        $requestedTime = $needsTime ? $this->parseEventTime($event, $data['requested_time_in']) : null;

        DB::transaction(function () use ($studentId, $event, $data, $type, $requestedTime) {
            // Serialise requests from the same student to avoid concurrent Pending duplicates.
            Student::whereKey($studentId)->lockForUpdate()->firstOrFail();
            $attendance = AttendanceRecord::where('event_id', $event->event_id)
                ->where('student_id', $studentId)->first();

            if ($type === 'Missing Attendance' && $attendance) {
                throw ValidationException::withMessages(['request_type' => 'An attendance record already exists. Choose Incorrect Status or Incorrect Time.']);
            }
            if ($type !== 'Missing Attendance' && !$attendance) {
                throw ValidationException::withMessages(['request_type' => 'No attendance record exists. Choose Missing Attendance instead.']);
            }
            if ($type === 'Incorrect Status' && $attendance->status === $data['requested_status']) {
                throw ValidationException::withMessages(['requested_status' => 'The requested status is already recorded.']);
            }
            if ($type === 'Incorrect Time' && $attendance->time_in && $attendance->time_in->equalTo($requestedTime)) {
                throw ValidationException::withMessages(['requested_time_in' => 'This time is already recorded.']);
            }
            if (AttendanceDiscrepancy::where('event_id', $event->event_id)
                ->where('student_id', $studentId)->where('status', 'Pending')->exists()) {
                throw ValidationException::withMessages(['event_id' => 'You already have a pending request for this event.']);
            }

            AttendanceDiscrepancy::create([
                'event_id' => $event->event_id,
                'student_id' => $studentId,
                'request_type' => $type,
                'requested_status' => $type === 'Incorrect Time' ? null : $data['requested_status'],
                'requested_time_in' => $requestedTime,
                'reason' => trim($data['reason']),
                'status' => 'Pending',
                'verification_status' => 'Unverified',
                'attendance_id' => $attendance?->attendance_id,
                'before_status' => $attendance?->status,
                'before_time_in' => $attendance?->time_in,
            ]);
        });

        return back()->with('success', 'Attendance discrepancy submitted for review. No attendance was changed yet.');
    }

    /** Personnel verify only claims for events assigned to them; they cannot alter attendance. */
    public function personnelIndex(Request $request): View
    {
        $personnelId = $request->user()->personnel?->personnel_id;
        abort_unless($personnelId, 403, 'No personnel record is linked to this account.');

        $discrepancies = AttendanceDiscrepancy::with(['event', 'student', 'verifier'])
            ->where('status', 'Pending')
            ->whereHas('event.assignments', fn ($q) => $q->where('personnel_id', $personnelId))
            ->orderByDesc('discrepancy_id')
            ->paginate(15);

        return view('personnel.attendance-discrepancies.index', compact('discrepancies'));
    }

    public function personnelVerify(Request $request, AttendanceDiscrepancy $attendanceDiscrepancy): RedirectResponse
    {
        $personnelId = $request->user()->personnel?->personnel_id;
        abort_unless($personnelId, 403);
        $data = $request->validate([
            'verification_status' => ['required', Rule::in(['Confirmed', 'Not Confirmed'])],
            'verification_notes' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        DB::transaction(function () use ($attendanceDiscrepancy, $personnelId, $data, $request) {
            $item = AttendanceDiscrepancy::whereKey($attendanceDiscrepancy->getKey())
                ->lockForUpdate()->firstOrFail();
            abort_unless($item->event->assignments()->where('personnel_id', $personnelId)->exists(), 403);
            if ($item->status !== 'Pending' || $item->verification_status !== 'Unverified') {
                throw ValidationException::withMessages(['verification_status' => 'This request is already reviewed or verified.']);
            }
            $item->update([
                'verification_status' => $data['verification_status'],
                'verification_notes' => trim($data['verification_notes']),
                'verified_by' => $request->user()->user_id,
                'verified_at' => now(),
            ]);
        });

        return back()->with('success', 'Personnel verification saved. Department Staff will make the final decision.');
    }

    /** Department Staff can view/review only discrepancies for their own department's events. */
    public function staffIndex(Request $request): View
    {
        $departmentId = $request->user()->department_id;
        abort_unless($departmentId, 403, 'No department is linked to this account.');
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['Pending', 'Approved', 'Rejected'])],
            'event_id' => ['nullable', 'integer'],
        ]);

        $events = Event::where('department_id', $departmentId)
            ->orderByDesc('event_date')->get(['event_id', 'event_name']);
        $discrepancies = AttendanceDiscrepancy::with(['event', 'student', 'verifier', 'reviewer'])
            ->whereHas('event', fn ($q) => $q->where('department_id', $departmentId))
            ->when(!empty($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['event_id']), fn ($q) => $q->where('event_id', $filters['event_id']))
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
            ->orderByDesc('discrepancy_id')
            ->paginate(15)->withQueryString();
        $pendingCount = AttendanceDiscrepancy::where('status', 'Pending')
            ->whereHas('event', fn ($q) => $q->where('department_id', $departmentId))->count();

        return view('staff.attendance-discrepancies.index', compact('events', 'discrepancies', 'pendingCount'));
    }

    public function staffReview(Request $request, AttendanceDiscrepancy $attendanceDiscrepancy): RedirectResponse
    {
        $departmentId = $request->user()->department_id;
        abort_unless($departmentId, 403);
        $data = $request->validate([
            'decision' => ['required', Rule::in(['Approved', 'Rejected'])],
            'review_notes' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        DB::transaction(function () use ($attendanceDiscrepancy, $departmentId, $data, $request) {
            $item = AttendanceDiscrepancy::whereKey($attendanceDiscrepancy->getKey())
                ->lockForUpdate()->firstOrFail();
            $event = Event::findOrFail($item->event_id);
            abort_unless((int) $event->department_id === (int) $departmentId, 403);
            if ($item->status !== 'Pending') {
                throw ValidationException::withMessages(['decision' => 'This request has already been reviewed.']);
            }

            $afterStatus = null;
            $afterTime = null;
            $attendanceId = $item->attendance_id;

            if ($data['decision'] === 'Approved') {

                // Require confirmation from Attendance Personnel.
                if ($item->verification_status !== 'Confirmed') {
                    throw ValidationException::withMessages([
                        'decision' => 'Attendance Personnel must confirm this discrepancy before approval.',
                    ]);
                }


                // Temporarily block corrections that could create
                // Present or Late without a verified Time Out.
                if ($item->request_type === 'Missing Attendance') {
                    throw ValidationException::withMessages([
                        'decision' => 'Missing Attendance approval requires verified Time In and Time Out. This correction workflow is not yet enabled.',
                    ]);
                }

                if ($item->request_type === 'Incorrect Status') {
                    $existingAttendance = AttendanceRecord::query()
                        ->where('event_id', $item->event_id)
                        ->where('student_id', $item->student_id)
                        ->first();

                    if (!$existingAttendance || !$existingAttendance->time_out) {
                        throw ValidationException::withMessages([
                            'decision' => 'Cannot approve Present or Late without a recorded Time Out.',
                        ]);
                    }
                }


                // Temporarily prevent unsafe Time In corrections.
                // Verified Time In/Time Out correction will be
                // implemented in a separate workflow.
                if ($item->request_type === 'Incorrect Time') {
                    throw ValidationException::withMessages([
                        'decision' => 'Incorrect Time approval is temporarily disabled until verified Time In and Time Out correction is supported.',
                    ]);
                }

                $event->syncScheduledStatus();
  
                if ($event->status !== 'Completed') {
                    throw ValidationException::withMessages(['decision' => 'Only completed events can be corrected.']);
                }
                $student = Student::whereKey($item->student_id)->lockForUpdate()->firstOrFail();
                if ($student->status !== 'Active') {
                    throw ValidationException::withMessages(['decision' => 'Student must be Active.']);
                }
                if (!EventEligibleStudent::where('event_id', $event->event_id)
                    ->where('student_id', $student->student_id)->exists()) {
                    throw ValidationException::withMessages(['decision' => 'Student is no longer eligible for this event.']);
                }

                $attendance = AttendanceRecord::where('event_id', $event->event_id)
                    ->where('student_id', $student->student_id)->lockForUpdate()->first();
                if ($item->request_type === 'Missing Attendance') {
                    if ($attendance) {
                        throw ValidationException::withMessages(['decision' => 'An attendance record already exists. Reject this outdated claim.']);
                    }
                    if (!$item->requested_status || !$item->requested_time_in) {
                        throw ValidationException::withMessages(['decision' => 'Request is missing the proposed attendance details.']);
                    }
                    $this->parseEventTime($event, $item->requested_time_in);
                    $attendance = AttendanceRecord::create([
                        'event_id' => $event->event_id,
                        'student_id' => $student->student_id,
                        'rfid_identifier' => 'MANUAL-DISCREPANCY-' . $item->discrepancy_id,
                        'time_in' => $item->requested_time_in,
                        'status' => $item->requested_status,
                        'scanned_by' => $request->user()->user_id,
                    ]);
                } else {
                    if (!$attendance) {
                        throw ValidationException::withMessages(['decision' => 'The original attendance record is missing. Reject this outdated claim.']);
                    }
                    // Refuse to overwrite a record modified since the request was filed.
                    $sameStatus = $attendance->status === $item->before_status;
                    $sameTime = $attendance->time_in?->toDateTimeString() === $item->before_time_in?->toDateTimeString();
                    if (!$sameStatus || !$sameTime) {
                        throw ValidationException::withMessages(['decision' => 'Attendance changed after submission. Reject the outdated request and ask the student to resubmit.']);
                    }
                    if ($item->request_type === 'Incorrect Status') {
                        if (!$item->requested_status) {
                            throw ValidationException::withMessages(['decision' => 'No correction status was specified.']);
                        }
                        $attendance->status = $item->requested_status;
                    } elseif ($item->request_type === 'Incorrect Time') {
                        if (!$item->requested_time_in) {
                            throw ValidationException::withMessages(['decision' => 'No correction time was specified.']);
                        }
                        $this->parseEventTime($event, $item->requested_time_in);
                        $attendance->time_in = $item->requested_time_in;
                    } else {
                        throw ValidationException::withMessages(['decision' => 'Unsupported discrepancy type.']);
                    }
                    $attendance->save();
                }
                $attendanceId = $attendance->attendance_id;
                $afterStatus = $attendance->status;
                $afterTime = $attendance->time_in;
            }

            $item->update([
                'status' => $data['decision'],
                'reviewed_by' => $request->user()->user_id,
                'reviewed_at' => now(),
                'review_notes' => trim($data['review_notes']),
                'attendance_id' => $attendanceId,
                'after_status' => $afterStatus,
                'after_time_in' => $afterTime,
            ]);
        });

        return back()->with('success', 'Discrepancy ' . strtolower($data['decision']) . '. ' . ($data['decision'] === 'Approved' ? 'Attendance record updated.' : 'No attendance changes made.'));
    }

    private function parseEventTime(Event $event, Carbon|string $value): Carbon
    {
        $time = Carbon::parse($value, self::TZ);
        $date = Carbon::parse($event->event_date)->format('Y-m-d');
        $start = Carbon::parse($date . ' ' . $event->start_time, self::TZ);
        $end = Carbon::parse($date . ' ' . $event->end_time, self::TZ);
        if ($time->lt($start) || $time->gt($end) || $time->gt(Carbon::now(self::TZ))) {
            throw ValidationException::withMessages(['requested_time_in' => 'Attendance time must be within the event schedule and not in the future (PH time).']);
        }
        return $time;
    }
}
