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
        | Completed / Cancelled Events Cannot Be Scanned
        |--------------------------------------------------------------------------
        */

        if (!$this->canScan($event)) {

            return redirect()
                ->route(
                    'my-events.show',
                    $event->event_id
                )
                ->with(
                    'error',
                    'Attendance scanning is no longer available for this event.'
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

    public function scan(
        Request $request,
        $event
    ) {

        $event =
            $this->assignedEvent(
                $event
            );


        /*
        |--------------------------------------------------------------------------
        | Event Status Check
        |--------------------------------------------------------------------------
        */

        if (!$this->canScan($event)) {

            return response()->json([

                'success' =>
                    false,

                'code' =>
                    'event_closed',

                'message' =>
                    'Attendance scanning is not available for this event.',

            ], 409);
        }


        /*
        |--------------------------------------------------------------------------
        | RFID Validation
        |--------------------------------------------------------------------------
        |
        | The current reader returns exactly 10 numeric characters.
        | RFID is treated as a string so leading zeroes are preserved.
        |
        */

        $rfid = trim(

            (string) $request->input(
                'rfid_identifier',
                ''
            )

        );


        $validator =
            Validator::make(

                [
                    'rfid_identifier' =>
                        $rfid,
                ],

                [
                    'rfid_identifier' => [
                        'required',
                        'string',
                        'regex:/^\d{10}$/',
                    ],
                ],

                [
                    'rfid_identifier.regex' =>
                        'The RFID must contain exactly 10 digits.',
                ]

            );


        if ($validator->fails()) {

            return response()->json([

                'success' =>
                    false,

                'code' =>
                    'invalid_rfid',

                'message' =>
                    $validator
                        ->errors()
                        ->first(
                            'rfid_identifier'
                        ),

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Record Attendance
        |--------------------------------------------------------------------------
        |
        | The student row is locked while checking/creating attendance.
        | This helps prevent two scanner requests for the same student
        | from creating duplicate records at the same time.
        |
        */

        return DB::transaction(
            function () use (
                $event,
                $rfid
            ) {


                $student =
                    Student::where(
                        'rfid_identifier',
                        $rfid
                    )
                    ->lockForUpdate()
                    ->first();


                /*
                |--------------------------------------------------------------------------
                | RFID Not Registered
                |--------------------------------------------------------------------------
                */

                if (!$student) {

                    return response()->json([

                        'success' =>
                            false,

                        'code' =>
                            'student_not_found',

                        'message' =>
                            'Student not found.',

                    ], 404);
                }


                /*
                |--------------------------------------------------------------------------
                | Inactive Student
                |--------------------------------------------------------------------------
                */

                if (
                    $student->status
                    !== 'Active'
                ) {

                    return response()->json([

                        'success' =>
                            false,

                        'code' =>
                            'student_inactive',

                        'message' =>
                            'This student account is inactive.',

                    ], 422);
                }


                /*
                |--------------------------------------------------------------------------
                | Duplicate Attendance Check
                |--------------------------------------------------------------------------
                */

                $existing =
                    AttendanceRecord::with(
                        'student'
                    )
                    ->where(
                        'event_id',
                        $event->event_id
                    )
                    ->where(
                        'student_id',
                        $student->student_id
                    )
                    ->first();


                if ($existing) {

                    return response()->json([

                        'success' =>
                            false,

                        'code' =>
                            'already_recorded',

                        'message' =>
                            'Student already recorded.',

                        'student' =>
                            $this->studentPayload(
                                $student
                            ),

                        'attendance' =>
                            $this->attendancePayload(
                                $existing
                            ),

                        'record' =>
                            $this->recordPayload(
                                $existing
                            ),

                    ], 409);
                }


                /*
                |--------------------------------------------------------------------------
                | Create Attendance Record
                |--------------------------------------------------------------------------
                */

                $attendance =
                    AttendanceRecord::create([

                        'event_id' =>
                            $event->event_id,

                        'student_id' =>
                            $student->student_id,

                        'rfid_identifier' =>
                            $rfid,

                        'time_in' =>
                            now(),

                        'status' =>
                            'Present',

                        'scanned_by' =>
                            auth()
                                ->user()
                                ->user_id,

                    ]);


                /*
                |--------------------------------------------------------------------------
                | Attach Student For Response
                |--------------------------------------------------------------------------
                */

                $attendance->setRelation(
                    'student',
                    $student
                );


                /*
                |--------------------------------------------------------------------------
                | Updated Attendance Count
                |--------------------------------------------------------------------------
                */

                $attendanceCount =
                    AttendanceRecord::where(
                        'event_id',
                        $event->event_id
                    )
                    ->count();


                return response()->json([

                    'success' =>
                        true,

                    'code' =>
                        'attendance_recorded',

                    'message' =>
                        'Attendance recorded.',


                    /*
                     * Kept for compatibility with the current scanner page.
                     */

                    'student' =>
                        $this->studentPayload(
                            $student
                        ),

                    'attendance' =>
                        $this->attendancePayload(
                            $attendance
                        ),


                    /*
                     * Normalized response used by the live feed.
                     */

                    'record' =>
                        $this->recordPayload(
                            $attendance
                        ),

                    'total_count' =>
                        $attendanceCount,

                ], 201);

            }
        );
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


        return Event::with(
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
    }


    /*
    |--------------------------------------------------------------------------
    | Scanner Availability
    |--------------------------------------------------------------------------
    */

    private function canScan(
        Event $event
    ): bool {

        return in_array(

            $event->status,

            [
                'Upcoming',
                'Ongoing',
            ],

            true

        );
    }


    /*
    |--------------------------------------------------------------------------
    | Student Response
    |--------------------------------------------------------------------------
    |
    | Only send information that the attendance screen needs.
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

        ];
    }
}