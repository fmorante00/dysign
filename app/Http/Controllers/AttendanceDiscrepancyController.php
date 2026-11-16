<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDiscrepancy;
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

        // Student-only read access. Filtering never changes discrepancy or attendance records.
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'request_type' => ['nullable', Rule::in(['Missing Attendance', 'Incorrect Status', 'Incorrect Time'])],
            'verification_status' => ['nullable', Rule::in(['Unverified', 'Confirmed', 'Not Confirmed'])],
            'status' => ['nullable', Rule::in(['Pending', 'Approved', 'Rejected'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
            'per_page' => ['nullable', 'integer', Rule::in([3, 5, 10])],
        ]);

        $base = AttendanceDiscrepancy::where('student_id', $studentId);
        $counts = [
            'all' => (clone $base)->count(),
            'pending' => (clone $base)->where('status', 'Pending')->count(),
            'verified' => (clone $base)->whereIn('verification_status', ['Confirmed', 'Not Confirmed'])->count(),
            'resolved' => (clone $base)->whereIn('status', ['Approved', 'Rejected'])->count(),
        ];

        $search = trim((string) ($filters['search'] ?? ''));
        $discrepancies = (clone $base)
            ->with(['event', 'reviewer', 'verifier', 'documents'])
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('event', function ($eventQuery) use ($search) {
                    $eventQuery->where('event_name', 'like', '%' . $search . '%');
                });
            })
            ->when(!empty($filters['request_type']), function ($query) use ($filters) {
                $query->where('request_type', $filters['request_type']);
            })
            ->when(!empty($filters['verification_status']), function ($query) use ($filters) {
                $query->where('verification_status', $filters['verification_status']);
            })
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->orderBy('discrepancy_id', ($filters['sort'] ?? 'newest') === 'oldest' ? 'asc' : 'desc')
            ->paginate((int) ($filters['per_page'] ?? 3))
            ->withQueryString();

        return view('student.attendance-discrepancies.index', compact('events', 'attendanceByEvent', 'discrepancies', 'counts', 'filters'));
    }

    public function studentStore(Request $request): RedirectResponse
    {
        $studentId = $request->user()->student_id;
        abort_unless($studentId, 403, 'No student record is linked to this account.');

        $data = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,event_id'],
            'request_type' => ['required', Rule::in(['Missing Attendance', 'Incorrect Status', 'Incorrect Time'])],
            'requested_status' => ['nullable', Rule::in(['Present', 'Late'])],
            'requested_time_in' => ['prohibited'],
            'reason' => ['required', 'string', 'min:10', 'max:2000'],
            'evidence' => ['nullable', 'array', 'max:3'],
            'evidence.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
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
        $needsStatus = $type === 'Incorrect Status';

        if ($needsStatus && empty($data['requested_status'])) {
            throw ValidationException::withMessages(['requested_status' => 'Select the requested attendance status.']);
        }

        $requestedTime = null;

        $files = $request->file('evidence', []);
        $storedFiles = [];

        try {
            DB::transaction(function () use ($studentId, $event, $data, $type, $requestedTime, $files, &$storedFiles) {
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

            if (AttendanceDiscrepancy::where('event_id', $event->event_id)
                ->where('student_id', $studentId)->where('status', 'Pending')->exists()) {
                throw ValidationException::withMessages(['event_id' => 'You already have a pending request for this event.']);
            }

            $discrepancy = AttendanceDiscrepancy::create([
                'event_id' => $event->event_id,
                'student_id' => $studentId,
                'request_type' => $type,
                'requested_status' => $type === 'Incorrect Status' ? $data['requested_status'] : null,
                'requested_time_in' => $requestedTime,
                'reason' => trim($data['reason']),
                'status' => 'Pending',
                'verification_status' => 'Unverified',
                'attendance_id' => $attendance?->attendance_id,
                'before_status' => $attendance?->status,
                'before_time_in' => $attendance?->time_in,
                'before_time_out' => $attendance?->time_out,
            ]);
            foreach ($files as $file) {
                $storedPath = $file->store('attendance_discrepancy_documents', 'local');

                if (!$storedPath) {
                    throw new \RuntimeException('Unable to save supporting evidence.');
                }

                $storedFiles[] = $storedPath;

                $discrepancy->documents()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $storedPath,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        });
        } catch (\Throwable $exception) {
            foreach ($storedFiles as $storedPath) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($storedPath);
            }

            throw $exception;
        }

        return back()->with('success', 'Attendance discrepancy submitted for review. No attendance was changed yet.');
    }

    /** Personnel verify only claims for events assigned to them; they cannot alter attendance. */
    public function personnelIndex(Request $request): View
    {
        $personnelId = $request->user()->personnel?->personnel_id;
        abort_unless($personnelId, 403, 'No personnel record is linked to this account.');

        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['Pending', 'Approved', 'Rejected'])],
            'verification_status' => ['nullable', Rule::in(['Unverified', 'Confirmed', 'Not Confirmed'])],
        ]);

        // All metrics and filtered results are restricted to assigned events.
        $assigned = AttendanceDiscrepancy::query()
            ->whereHas('event.assignments', fn ($q) => $q->where('personnel_id', $personnelId));

        $counts = [
            'all' => (clone $assigned)->count(),
            'awaiting' => (clone $assigned)->where('status', 'Pending')
                ->where('verification_status', 'Unverified')->count(),
            'verified' => (clone $assigned)->where('verification_status', '!=', 'Unverified')->count(),
            'resolved' => (clone $assigned)->whereIn('status', ['Approved', 'Rejected'])->count(),
        ];

        $discrepancies = (clone $assigned)
            ->with(['event', 'student', 'verifier', 'reviewer', 'attendance'])
            ->when(!empty($filters['status']),
                fn ($q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['verification_status']),
                fn ($q) => $q->where('verification_status', $filters['verification_status']))
            ->orderByRaw("CASE WHEN status = 'Pending' AND verification_status = 'Unverified' THEN 0 WHEN status = 'Pending' THEN 1 ELSE 2 END")
            ->orderByDesc('discrepancy_id')
            ->paginate(15)
            ->withQueryString();

        return view('personnel.attendance-discrepancies.index', compact('discrepancies', 'counts', 'filters'));
    }

    public function personnelVerify(Request $request, AttendanceDiscrepancy $attendanceDiscrepancy): RedirectResponse
    {
        $personnelId = $request->user()->personnel?->personnel_id;
        abort_unless($personnelId, 403);

        // The browser is not an authoritative source for attendance timestamps.
        $data = $request->validate([
            'verification_status' => ['required', Rule::in(['Confirmed', 'Not Confirmed'])],
            'verification_notes' => ['required', 'string', 'min:10', 'max:2000'],
            'verified_time_in' => ['prohibited'],
            'verified_time_out' => ['prohibited'],
        ]);

        DB::transaction(function () use ($attendanceDiscrepancy, $personnelId, $data, $request) {
            $item = AttendanceDiscrepancy::whereKey($attendanceDiscrepancy->getKey())
                ->lockForUpdate()->firstOrFail();
            abort_unless($item->event->assignments()->where('personnel_id', $personnelId)->exists(), 403);
            if ($item->status !== 'Pending' || $item->verification_status !== 'Unverified') {
                throw ValidationException::withMessages([
                    'verification_status' => 'This request is already reviewed or verified.',
                ]);
            }

            $verifiedTimeIn = null;
            $verifiedTimeOut = null;
            $notes = trim($data['verification_notes']);

            if ($data['verification_status'] === 'Confirmed') {
                // Prefer existing complete attendance for status disputes, or genuinely
                // captured personnel check-in/out records for missing/incorrect times.
                [$verifiedTimeIn, $verifiedTimeOut, $source] = $this->capturedTimesFor($item);

                if ($verifiedTimeIn && $verifiedTimeOut) {
                    $this->validateCapturedTimes($item->event, $verifiedTimeIn, $verifiedTimeOut);
                    $notes .= "\n[System-captured time source: {$source}]";
                } elseif ($item->request_type === 'Missing Attendance') {
                    // The claim can be confirmed by independent non-timestamp evidence,
                    // but approving it MUST NOT fabricate a historical check-in/out.
                    // Staff can only record an Incomplete attendance in this case.
                    $notes .= "\n[No captured Time In/Out. Any approved record must remain Incomplete.]";
                } else {
                    throw ValidationException::withMessages([
                        'verification_status' => 'No independently captured Time In and Time Out are available for this correction. Do not invent times; choose Not Confirmed and explain the available evidence.',
                    ]);
                }
            }

            $item->update([
                'verification_status' => $data['verification_status'],
                'verification_notes' => $notes,
                'verified_time_in' => $verifiedTimeIn,
                'verified_time_out' => $verifiedTimeOut,
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
            $afterTimeOut = null;
            $attendanceId = $item->attendance_id;

            if ($data['decision'] === 'Approved') {

                // Require confirmation from Attendance Personnel.
                if ($item->verification_status !== 'Confirmed') {
                    throw ValidationException::withMessages([
                        'decision' => 'Attendance Personnel must confirm this discrepancy before approval.',
                    ]);
                }

                // Do not rely on previously entered dates or times. Any recorded
                // verified pair must still match its independent system-captured source.
                if ($item->verified_time_in || $item->verified_time_out) {
                    [$capturedIn, $capturedOut] = $this->capturedTimesFor($item);
                    if (!$capturedIn || !$capturedOut
                        || $capturedIn->toDateTimeString() !== $item->verified_time_in?->toDateTimeString()
                        || $capturedOut->toDateTimeString() !== $item->verified_time_out?->toDateTimeString()) {
                        throw ValidationException::withMessages([
                            'decision' => 'Captured time evidence is missing or has changed. Reject this outdated request and ask for a new verification.',
                        ]);
                    }
                    $this->validateCapturedTimes($event, $capturedIn, $capturedOut);
                } elseif ($item->request_type !== 'Missing Attendance') {
                    throw ValidationException::withMessages([
                        'decision' => 'This correction cannot be approved without independently captured attendance times.',
                    ]);
                }

                // Missing attendance without captured times can only become Incomplete.
                // Never turn an event schedule, file upload, or review timestamp
                // into a fictitious historical check-in/check-out.

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
                        throw ValidationException::withMessages([
                            'decision' => 'An attendance record already exists. Reject this outdated claim.',
                        ]);
                    }

                    if (!$item->verified_time_in && !$item->verified_time_out) {
                        // A human-approved attendance claim can be documented without
                        // pretending we know when a scan occurred. A completed
                        // Present/Late attendance still requires real captured times.
                        $attendance = AttendanceRecord::create([
                            'event_id' => $event->event_id,
                            'student_id' => $student->student_id,
                            'rfid_identifier' => 'MANUAL-DISCREPANCY-' . $item->discrepancy_id,
                            'time_in' => null,
                            'time_out' => null,
                            'status' => 'Incomplete',
                            'scanned_by' => null,
                        ]);
                    } else {
                        if (!$item->verified_time_in || !$item->verified_time_out) {
                            throw ValidationException::withMessages([
                                'decision' => 'A partial timestamp pair cannot be approved.',
                            ]);
                        }

                    // Interpret stored datetime values as Philippine local time.
                    $verifiedIn = Carbon::parse(
                        $item->verified_time_in->format('Y-m-d H:i:s'),
                        self::TZ
                    );

                    $verifiedOut = Carbon::parse(
                        $item->verified_time_out->format('Y-m-d H:i:s'),
                        self::TZ
                    );

                    $this->parseEventTime($event, $verifiedIn);

                    $eventDate = Carbon::parse($event->event_date)
                        ->format('Y-m-d');

                    $eventStart = Carbon::parse(
                        $eventDate . ' ' . $event->start_time,
                        self::TZ
                    );

                    $eventEnd = Carbon::parse(
                        $eventDate . ' ' . $event->end_time,
                        self::TZ
                    );

                    $checkoutDeadline = $eventEnd->copy()->addHour();

                    if (
                        $verifiedOut->lt($eventEnd) ||
                        $verifiedOut->gt($checkoutDeadline) ||
                        $verifiedOut->lte($verifiedIn) ||
                        $verifiedOut->gt(Carbon::now(self::TZ))
                    ) {
                        throw ValidationException::withMessages([
                            'decision' => 'Verified Time Out is outside the allowed checkout window.',
                        ]);
                    }

                    $derivedStatus = $verifiedIn->gt($eventStart)
                        ? 'Late'
                        : 'Present';



                    $attendance = AttendanceRecord::create([
                        'event_id' => $event->event_id,
                        'student_id' => $student->student_id,
                        'rfid_identifier' => 'MANUAL-DISCREPANCY-' . $item->discrepancy_id,
                        'time_in' => $verifiedIn,
                        'time_out' => $verifiedOut,
                        'status' => $derivedStatus,
                        'scanned_by' => null,
                    ]);
                    }
                } else {
                    if (!$attendance) {
                        throw ValidationException::withMessages(['decision' => 'The original attendance record is missing. Reject this outdated claim.']);
                    }
                    // Refuse to overwrite a record modified since the request was filed.
                    $sameStatus = $attendance->status === $item->before_status;
                    $sameTime = $attendance->time_in?->toDateTimeString() === $item->before_time_in?->toDateTimeString();
                    $sameTimeOut = $attendance->time_out?->toDateTimeString() === $item->before_time_out?->toDateTimeString();

                    if (!$sameStatus || !$sameTime || !$sameTimeOut) {
                        throw ValidationException::withMessages(['decision' => 'Attendance changed after submission. Reject the outdated request and ask the student to resubmit.']);
                    }
                    if ($item->request_type === 'Incorrect Status') {
                        if (
                            !$item->requested_status ||
                            !$item->verified_time_in ||
                            !$item->verified_time_out ||
                            !$attendance->time_in ||
                            !$attendance->time_out
                        ) {
                            throw ValidationException::withMessages([
                                'decision' => 'Status correction requires complete recorded and verified Time In/Out.',
                            ]);
                        }

                        $verifiedIn = Carbon::parse(
                            $item->verified_time_in->format('Y-m-d H:i:s'),
                            self::TZ
                        );
                        $verifiedOut = Carbon::parse(
                            $item->verified_time_out->format('Y-m-d H:i:s'),
                            self::TZ
                        );

                        $recordedIn = Carbon::parse(
                            $attendance->time_in->format('Y-m-d H:i:s'),
                            self::TZ
                        );
                        $recordedOut = Carbon::parse(
                            $attendance->time_out->format('Y-m-d H:i:s'),
                            self::TZ
                        );

                        $this->parseEventTime($event, $verifiedIn);

                        $eventDate = Carbon::parse($event->event_date)
                            ->format('Y-m-d');

                        $eventStart = Carbon::parse(
                            $eventDate . ' ' . $event->start_time,
                            self::TZ
                        );

                        $eventEnd = Carbon::parse(
                            $eventDate . ' ' . $event->end_time,
                            self::TZ
                        );

                        if (
                            $verifiedOut->lt($eventEnd) ||
                            $verifiedOut->gt($eventEnd->copy()->addHour()) ||
                            $verifiedOut->lte($verifiedIn) ||
                            $verifiedOut->gt(Carbon::now(self::TZ))
                        ) {
                            throw ValidationException::withMessages([
                                'decision' => 'Verified checkout time is outside the valid attendance window.',
                            ]);
                        }

                        if (
                            abs($verifiedIn->diffInSeconds($recordedIn)) > 59 ||
                            abs($verifiedOut->diffInSeconds($recordedOut)) > 59
                        ) {
                            throw ValidationException::withMessages([
                                'decision' => 'Verified times differ from the recorded times. Submit an Incorrect Time request instead.',
                            ]);
                        }

                        $derivedStatus = $recordedIn->gt($eventStart)
                            ? 'Late'
                            : 'Present';

                        $verifiedStatus = $verifiedIn->gt($eventStart)
                            ? 'Late'
                            : 'Present';

                        if (
                            $derivedStatus !== $verifiedStatus ||
                            $item->requested_status !== $derivedStatus
                        ) {
                            throw ValidationException::withMessages([
                                'decision' => 'Requested status does not match the verified attendance evidence.',
                            ]);
                        }

                        if ($attendance->status === $derivedStatus) {
                            throw ValidationException::withMessages([
                                'decision' => 'Attendance already has the correct status.',
                            ]);
                        }

                        $attendance->status = $derivedStatus;
                    } elseif ($item->request_type === 'Incorrect Time') {
                        if (
                            !$item->verified_time_in ||
                            !$item->verified_time_out
                        ) {
                            throw ValidationException::withMessages([
                                'decision' => 'Incorrect Time correction requires independently verified Time In and Time Out.',
                            ]);
                        }

                        $verifiedIn = Carbon::parse(
                            $item->verified_time_in->format('Y-m-d H:i:s'),
                            self::TZ
                        );

                        $verifiedOut = Carbon::parse(
                            $item->verified_time_out->format('Y-m-d H:i:s'),
                            self::TZ
                        );

                        $this->parseEventTime($event, $verifiedIn);

                        $eventDate = Carbon::parse($event->event_date)
                            ->format('Y-m-d');

                        $eventStart = Carbon::parse(
                            $eventDate . ' ' . $event->start_time,
                            self::TZ
                        );

                        $eventEnd = Carbon::parse(
                            $eventDate . ' ' . $event->end_time,
                            self::TZ
                        );

                        if (
                            $verifiedOut->lt($eventEnd) ||
                            $verifiedOut->gt($eventEnd->copy()->addHour()) ||
                            $verifiedOut->lte($verifiedIn) ||
                            $verifiedOut->gt(Carbon::now(self::TZ))
                        ) {
                            throw ValidationException::withMessages([
                                'decision' => 'Verified Time Out is outside the official checkout window.',
                            ]);
                        }

                        $recordedIn = $attendance->time_in?->toDateTimeString();
                        $recordedOut = $attendance->time_out?->toDateTimeString();

                        if (
                            $recordedIn === $verifiedIn->toDateTimeString() &&
                            $recordedOut === $verifiedOut->toDateTimeString()
                        ) {
                            throw ValidationException::withMessages([
                                'decision' => 'The verified times already match the attendance record.',
                            ]);
                        }

                        $attendance->time_in = $verifiedIn;
                        $attendance->time_out = $verifiedOut;

                        $attendance->status = $verifiedIn->gt($eventStart)
                            ? 'Late'
                            : 'Present';
                    } else {
                        throw ValidationException::withMessages(['decision' => 'Unsupported discrepancy type.']);
                    }
                    $attendance->save();
                }
                $attendanceId = $attendance->attendance_id;
                $afterStatus = $attendance->status;
                $afterTime = $attendance->time_in;
                $afterTimeOut = $attendance->time_out;
            }

            $item->update([
                'status' => $data['decision'],
                'reviewed_by' => $request->user()->user_id,
                'reviewed_at' => now(),
                'review_notes' => trim($data['review_notes']),
                'attendance_id' => $attendanceId,
                'after_status' => $afterStatus,
                'after_time_in' => $afterTime,
                'after_time_out' => $afterTimeOut,
            ]);
        });

        return back()->with('success', 'Discrepancy ' . strtolower($data['decision']) . '. ' . ($data['decision'] === 'Approved' ? 'Attendance record updated.' : 'No attendance changes made.'));
    }

    /**
     * @return array{0: ?Carbon, 1: ?Carbon, 2: ?string}
     * Only use time pairs already captured during a real scan or a live,
     * personnel-recorded manual exception. Never derive a time from an event
     * schedule, uploaded photo, the student's statement, or the review clock.
     */
    private function capturedTimesFor(AttendanceDiscrepancy $item): array
    {
        // An Incorrect Status claim disputes the label, not its recorded times.
        if ($item->request_type === 'Incorrect Status') {
            $attendance = AttendanceRecord::query()
                ->where('event_id', $item->event_id)
                ->where('student_id', $item->student_id)
                ->first();

            if ($attendance?->time_in && $attendance?->time_out) {
                return [
                    $this->asPhilippineTime($attendance->time_in),
                    $this->asPhilippineTime($attendance->time_out),
                    'existing attendance record #' . $attendance->attendance_id,
                ];
            }
        }

        // Attendance Exceptions capture real-time manual check-in and checkout
        // using the server clock. This can provide independent timestamps for
        // a Missing Attendance or Incorrect Time discrepancy.
        $exception = AttendanceException::query()
            ->where('event_id', $item->event_id)
            ->where('student_id', $item->student_id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->whereNotNull('observed_at')
            ->whereNotNull('checked_out_at')
            ->whereNotNull('checked_out_by')
            ->orderByDesc('exception_id')
            ->first();

        if ($exception && $exception->created_at) {
            // Only accept a live check-in: modern Manual Exceptions save
            // observed_at with the server clock in the same transaction as
            // created_at. Older manually entered historical times are not an
            // automatic timestamp source.
            $liveCheckIn = abs($exception->observed_at->diffInSeconds($exception->created_at)) <= 180;
            if ($liveCheckIn) {
                return [
                    $this->asPhilippineTime($exception->observed_at),
                    $this->asPhilippineTime($exception->checked_out_at),
                    'personnel-captured manual attendance exception #' . $exception->exception_id,
                ];
            }
        }

        return [null, null, null];
    }

    private function asPhilippineTime(Carbon $value): Carbon
    {
        // These app DATETIME fields are stored as local PH wall-clock values.
        return Carbon::parse($value->format('Y-m-d H:i:s'), self::TZ);
    }

    private function validateCapturedTimes(Event $event, Carbon $timeIn, Carbon $timeOut): void
    {
        $this->parseEventTime($event, $timeIn);
        $date = Carbon::parse($event->event_date)->format('Y-m-d');
        $eventEnd = Carbon::parse($date . ' ' . $event->end_time, self::TZ);

        if ($timeOut->lte($timeIn)
            || $timeOut->lt($eventEnd)
            || $timeOut->gt($eventEnd->copy()->addHour())
            || $timeOut->gt(Carbon::now(self::TZ))) {
            throw ValidationException::withMessages([
                'verification_status' => 'System-captured Time Out is outside the valid event checkout window.',
            ]);
        }
    }

    private function parseEventTime(Event $event, Carbon|string $value): Carbon
    {
        $time = Carbon::parse($value, self::TZ);
        $date = Carbon::parse($event->event_date)->format('Y-m-d');
        $start = Carbon::parse($date . ' ' . $event->start_time, self::TZ);
        $end = Carbon::parse($date . ' ' . $event->end_time, self::TZ);
        if ($time->lt($start->copy()->subHour()) || $time->gte($end) || $time->gt(Carbon::now(self::TZ))) {
            throw ValidationException::withMessages(['decision' => 'Captured Time In must be within one hour before event start and strictly before event end. Future times are not allowed (PH time).']);
        }
        return $time;
    }

    public function evidence(
        Request $request,
        AttendanceDiscrepancy $attendanceDiscrepancy,
        \App\Models\AttendanceDiscrepancyDocument $document
    ) {
        $user = $request->user();
        $routeName = $request->route()->getName();

        switch ($routeName) {
            case 'student.attendance-discrepancies.evidence':
                abort_unless(
                    $user->student_id &&
                    (int) $user->student_id === (int) $attendanceDiscrepancy->student_id,
                    403
                );
                break;

            case 'personnel.attendance-discrepancies.evidence':
                $personnelId = $user->personnel?->personnel_id;

                abort_unless(
                    $personnelId &&
                    $attendanceDiscrepancy->event->assignments()
                        ->where('personnel_id', $personnelId)->exists(),
                    403
                );
                break;

            case 'staff.attendance-discrepancies.evidence':
                abort_unless(
                    $user->department_id &&
                    (int) $user->department_id ===
                    (int) $attendanceDiscrepancy->event->department_id,
                    403
                );
                break;

            default:
                abort(403);
        }

        abort_unless(
            (int) $document->discrepancy_id ===
            (int) $attendanceDiscrepancy->discrepancy_id,
            404
        );

        abort_unless(
            str_starts_with(
                $document->file_path,
                'attendance_discrepancy_documents/'
            ),
            404
        );

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        abort_unless(
            isset($extensions[$document->mime_type]),
            404
        );

        $disk = \Illuminate\Support\Facades\Storage::disk('local');

        abort_unless($disk->exists($document->file_path), 404);

        return $disk->response(
            $document->file_path,
            'attendance-evidence-' . $document->document_id .
                '.' . $extensions[$document->mime_type],
            [
                'Content-Type' => $document->mime_type,
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ],
            'inline'
        );
    }
}
