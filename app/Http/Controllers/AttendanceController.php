<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Attendance Scanner Page
    |--------------------------------------------------------------------------
    */

    public function index($event)
    {
        $event =
            $this->assignedEvent(
                $event
            );


        /*
        |--------------------------------------------------------------------------
        | Scanner Availability
        |--------------------------------------------------------------------------
        |
        | The event status is automatically synchronized inside assignedEvent().
        | RFID scanning is available only while the event is actually Ongoing.
        |
        */

        if (!$this->canScan($event)) {

            $message =
                $event->status === 'Cancelled'
                    ? 'Attendance scanning is not available for a cancelled event.'
                    : 'Scanner access opens one hour before the event and ends one hour after event completion.';


            return redirect()
                ->route(
                    'my-events.show',
                    $event->event_id
                )
                ->with(
                    'error',
                    $message
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Attendance Information
        |--------------------------------------------------------------------------
        |
        | Only the latest 20 records are loaded into the live feed.
        | The total count is calculated separately.
        |
        */

        $attendanceCount =
            AttendanceRecord::where(
                'event_id',
                $event->event_id
            )
            ->count();


        $attendanceRecords =
            AttendanceRecord::with(
                'student'
            )
            ->where(
                'event_id',
                $event->event_id
            )
            ->orderByDesc(
                'time_in'
            )
            ->take(20)
            ->get();


        $latestAttendance =
            $attendanceRecords->first();


        return view(
            'attendance.index',
            compact(
                'event',
                'attendanceRecords',
                'attendanceCount',
                'latestAttendance'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scan RFID
    |--------------------------------------------------------------------------
    */

    /**
     * RFID scan policy:
     * - First scan: from start minus 1 hour until strictly before event end.
     * - Second scan: from event end until end plus 1 hour (inclusive).
     * - Required students: automatically eligible, no registration row needed.
     * - Optional students: must have an active registration for the event.
     * - The first scan is NEVER Present/Late; it remains Checked In.
     */
    public function scan(Request $request, $event)
    {
        $event = $this->assignedEvent($event);
        $rfid = trim((string) $request->input('rfid_identifier', ''));

        $validator = Validator::make(
            ['rfid_identifier' => $rfid],
            ['rfid_identifier' => ['required', 'string', 'regex:/^\\d{10}$/']],
            ['rfid_identifier.regex' => 'The RFID must contain exactly 10 digits.']
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 'invalid_rfid',
                'message' => $validator->errors()->first('rfid_identifier'),
            ], 422);
        }

        return DB::transaction(function () use ($event, $rfid) {
            // Serialize status/attendance mutations and retain event assignment authorization.
            $event = Event::where('event_id', $event->event_id)->lockForUpdate()->firstOrFail();
            $event = $this->syncEventStatus($event);
            $now = Carbon::now('Asia/Manila');
            $window = $this->attendanceWindow($event);
            $phase = $this->scanPhase($event, $now);

            if (!$window || $phase === 'closed') {
                return response()->json([
                    'success' => false,
                    'code' => 'event_closed',
                    'message' => 'RFID attendance is not available. Check the event time and check-in / check-out window.',
                    'event_status' => $event->status,
                ], 409);
            }

            $student = Student::where('rfid_identifier', $rfid)->lockForUpdate()->first();
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'code' => 'student_not_found',
                    'message' => 'Student not found.',
                ], 404);
            }

            // Preserve the existing active-card lifecycle check (lost/replaced cards stay invalid).
            $hasActiveCard = DB::table('rfid_card_assignments')
                ->where('student_id', $student->student_id)
                ->where('rfid_identifier', $rfid)
                ->where('status', 'Active')
                ->exists();
            if (!$hasActiveCard) {
                return response()->json([
                    'success' => false,
                    'code' => 'rfid_card_inactive',
                    'message' => 'This RFID card is lost, replaced, or not activated.',
                ], 403);
            }

            if ($student->status !== 'Active') {
                return response()->json([
                    'success' => false,
                    'code' => 'student_inactive',
                    'message' => 'This student account is inactive.',
                ], 422);
            }

            $existing = AttendanceRecord::with('student')
                ->where('event_id', $event->event_id)
                ->where('student_id', $student->student_id)
                ->lockForUpdate()
                ->first();

            // A second scan can finalize only a new Checked In record, during the checkout window.
            if ($existing) {
                if ($existing->status !== 'Checked In' || $existing->time_out !== null) {
                    return response()->json([
                        'success' => false,
                        'code' => 'already_recorded',
                        'message' => 'Attendance already has a final or historical status. Please use the discrepancy process for corrections.',
                        'student' => $this->studentPayload($student),
                        'attendance' => $this->attendancePayload($existing),
                        'record' => $this->recordPayload($existing),
                    ], 409);
                }
                if ($phase !== 'check_out') {
                    return response()->json([
                        'success' => false,
                        'code' => 'checkout_not_open',
                        'message' => 'Time In is already recorded. Time Out starts at the scheduled event end.',
                        'student' => $this->studentPayload($student),
                        'attendance' => $this->attendancePayload($existing),
                        'record' => $this->recordPayload($existing),
                    ], 409);
                }

                $checkInAt = Carbon::parse($existing->time_in)->setTimezone('Asia/Manila');
                $existing->time_out = $now;
                $existing->status = $checkInAt->gt($window['start']) ? 'Late' : 'Present';
                $existing->save();
                $existing->setRelation('student', $student);

                return response()->json([
                    'success' => true,
                    'code' => 'checkout_recorded',
                    'message' => 'Time Out recorded. Attendance is now '.$existing->status.'.',
                    'student' => $this->studentPayload($student),
                    'attendance' => $this->attendancePayload($existing),
                    'record' => $this->recordPayload($existing),
                    'total_count' => AttendanceRecord::where('event_id', $event->event_id)->count(),
                ]);
            }

            if ($phase === 'check_out') {
                return response()->json([
                    'success' => false,
                    'code' => 'checkin_missing',
                    'message' => 'Time Out cannot be recorded without an earlier Time In.',
                ], 422);
            }

            // Eligibility is checked for the first scan, before creating an attendance record.
            $participationType = DB::table('event_eligible_students')
                ->where('event_id', $event->event_id)
                ->where('student_id', $student->student_id)
                ->value('participation_type');
            if (!in_array($participationType, ['Required', 'Optional'], true)) {
                return response()->json([
                    'success' => false,
                    'code' => 'not_eligible',
                    'message' => 'This student is not included in the eligible participants for this event.',
                ], 403);
            }

            if ($participationType === 'Optional') {
                $registered = DB::table('event_registrations')
                    ->where('event_id', $event->event_id)
                    ->where('student_id', $student->student_id)
                    ->where('status', 'Registered')
                    ->exists();
                if (!$registered) {
                    return response()->json([
                        'success' => false,
                        'code' => 'registration_required',
                        'message' => 'Optional event: an active registration is required before check-in.',
                    ], 403);
                }
            }

            $attendance = AttendanceRecord::create([
                'event_id' => $event->event_id,
                'student_id' => $student->student_id,
                'rfid_identifier' => $rfid,
                'time_in' => $now,
                'status' => 'Checked In',
                'scanned_by' => auth()->user()->user_id,
            ]);
            $attendance->setRelation('student', $student);

            return response()->json([
                'success' => true,
                'code' => 'checkin_recorded',
                'message' => 'Time In recorded. Attendance is pending valid Time Out.',
                'student' => $this->studentPayload($student),
                'attendance' => $this->attendancePayload($attendance),
                'record' => $this->recordPayload($attendance),
                'total_count' => AttendanceRecord::where('event_id', $event->event_id)->count(),
            ], 201);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Live Attendance Feed
    |--------------------------------------------------------------------------
    |
    | This endpoint is used by the scanner page to refresh
    | the live feed.
    |
    */

    public function feed($event)
    {
        $event =
            $this->assignedEvent(
                $event
            );


        $attendanceCount =
            AttendanceRecord::where(
                'event_id',
                $event->event_id
            )
            ->count();


        $attendanceRecords =
            AttendanceRecord::with(
                'student'
            )
            ->where(
                'event_id',
                $event->event_id
            )
            ->orderByDesc(
                'time_in'
            )
            ->take(20)
            ->get();


        return response()->json([

            'success' =>
                true,

            'event_id' =>
                $event->event_id,

            'event_status' =>
                $event->status,

            'scannable' =>
                $this->canScan(
                    $event
                ),

            'total_count' =>
                $attendanceCount,

            'records' =>
                $attendanceRecords
                    ->map(
                        function ($record) {

                            return
                                $this->recordPayload(
                                    $record
                                );

                        }
                    )
                    ->values(),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned Event Authorization
    |--------------------------------------------------------------------------
    |
    | Attendance Personnel may only access events assigned
    | to their own personnel record.
    |
    */

    private function assignedEvent(
        $event
    ): Event {

        $user =
            auth()->user();


        $personnel =
            $user->personnel;


        if (!$personnel) {

            abort(
                403,
                'No personnel profile is linked to this account.'
            );
        }


        $assignedEvent =
            Event::with(
                'department'
            )
            ->where(
                'event_id',
                $event
            )
            ->whereHas(
                'assignments',
                function ($query) use (
                    $personnel
                ) {

                    $query->where(
                        'personnel_id',
                        $personnel
                            ->personnel_id
                    );

                }
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Automatically Synchronize Status
        |--------------------------------------------------------------------------
        */

        return $this->syncEventStatus(
            $assignedEvent
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Automatic Event Status
    |--------------------------------------------------------------------------
    |
    | Event status calculation is centralized in the Event model.
    | This avoids duplicating date/time parsing logic in this controller.
    |
    */

    private function syncEventStatus(
        Event $event
    ): Event {

        /*
        |--------------------------------------------------------------------------
        | Synchronize Using Event Model
        |--------------------------------------------------------------------------
        |
        | Event::syncScheduledStatus() safely normalizes event_date,
        | start_time, and end_time before comparing them.
        |
        */

        $event->syncScheduledStatus();


        /*
        |--------------------------------------------------------------------------
        | Refresh Current Event Data
        |--------------------------------------------------------------------------
        */

        $event->refresh();

        $event->load(
            'department'
        );


        return $event;
    }


    /*
    |--------------------------------------------------------------------------
    | Scanner Availability
    |--------------------------------------------------------------------------
    |
    | RFID scanning is allowed ONLY while the event is Ongoing.
    |
    */

    /**
     * A fixed, timezone-aware event attendance window (same date start/end).
     * Event status may be Upcoming or Completed while scans are still valid.
     */
    private function attendanceWindow(Event $event): ?array
    {
        if ($event->status === 'Cancelled' || !$event->event_date || !$event->start_time || !$event->end_time) {
            return null;
        }

        $date = Carbon::parse($event->event_date)->format('Y-m-d');
        $start = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $date.' '.Carbon::parse($event->start_time)->format('H:i:s'),
            'Asia/Manila'
        );
        $end = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $date.' '.Carbon::parse($event->end_time)->format('H:i:s'),
            'Asia/Manila'
        );

        // Do not silently invent an overnight attendance policy for invalid schedules.
        if ($end->lte($start)) {
            return null;
        }
        return [
            'start' => $start,
            'end' => $end,
            'checkin_open' => $start->copy()->subHour(),
            'checkout_close' => $end->copy()->addHour(),
        ];
    }

    private function scanPhase(Event $event, Carbon $at): string
    {
        $window = $this->attendanceWindow($event);
        if (!$window || $at->lt($window['checkin_open']) || $at->gt($window['checkout_close'])) {
            return 'closed';
        }
        if ($at->lt($window['end'])) {
            return 'check_in';
        }
        return 'check_out';
    }

    private function canScan(Event $event): bool
    {
        return $this->scanPhase($event, Carbon::now('Asia/Manila')) !== 'closed';
    }


    /*
    |--------------------------------------------------------------------------
    | Student Response
    |--------------------------------------------------------------------------
    |
    | Student photo and year level are also returned so the
    | attendance scanner can show the student's identity after a tap.
    |
    */

    private function studentPayload(
        Student $student
    ): array {

        return [

            'student_id' =>
                $student->student_id,

            'student_number' =>
                $student->student_number,

            'first_name' =>
                $student->first_name,

            'last_name' =>
                $student->last_name,

            'program_name' =>
                $student->program_name,

            'program_code' =>
                $student->program_code,

            'year_level' =>
                $student->year_level,

            'photo_url' =>
                $this->studentPhotoUrl(
                    $student
                ),

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Response
    |--------------------------------------------------------------------------
    */

    private function attendancePayload(
        AttendanceRecord $attendance
    ): array {

        $timeIn =
            Carbon::parse(
                $attendance->time_in
            );


        return [

            'attendance_id' =>
                $attendance
                    ->attendance_id,

            'time_in' =>
                $timeIn
                    ->toIso8601String(),

            'time_display' =>
                $timeIn
                    ->format(
                        'h:i A'
                    ),

            'date_display' =>
                $timeIn
                    ->format(
                        'M d, Y'
                    ),

            'status' =>
                $attendance->status,

            'time_out' => $attendance->time_out
                ? Carbon::parse($attendance->time_out)->toIso8601String()
                : null,

            'time_out_display' => $attendance->time_out
                ? Carbon::parse($attendance->time_out)->format('h:i A')
                : null,

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Live Feed Record Response
    |--------------------------------------------------------------------------
    */

    private function recordPayload(
        AttendanceRecord $record
    ): array {

        $student =
            $record->student;


        $timeIn =
            Carbon::parse(
                $record->time_in
            );


        return [

            'attendance_id' =>
                $record
                    ->attendance_id,

            'student_number' =>
                $student
                    ?->student_number,

            'first_name' =>
                $student
                    ?->first_name,

            'last_name' =>
                $student
                    ?->last_name,

            'program_code' =>
                $student
                    ?->program_code,

            'program_name' =>
                $student
                    ?->program_name,

            'year_level' =>
                $student
                    ?->year_level,

            'photo_url' =>
                $student
                    ? $this->studentPhotoUrl(
                        $student
                    )
                    : null,

            'time_in' =>
                $timeIn
                    ->toIso8601String(),

            'time_display' =>
                $timeIn
                    ->format(
                        'h:i A'
                    ),

            'date_display' =>
                $timeIn
                    ->format(
                        'M d, Y'
                    ),

            'status' =>
                $record->status,

            'time_out' => $record->time_out
                ? Carbon::parse($record->time_out)->toIso8601String()
                : null,

            'time_out_display' => $record->time_out
                ? Carbon::parse($record->time_out)->format('h:i A')
                : null,

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Student Photo URL
    |--------------------------------------------------------------------------
    |
    | Student images are stored in:
    | public/student_photos/
    |
    | basename() keeps the generated public URL safe even if photo_path
    | already contains a folder name.
    |
    */

    private function studentPhotoUrl(
        Student $student
    ): ?string {

        if (
            empty(
                $student->photo_path
            )
        ) {

            return null;
        }


        return asset(
            'student_photos/'
            . basename(
                $student->photo_path
            )
        );
    }
}
