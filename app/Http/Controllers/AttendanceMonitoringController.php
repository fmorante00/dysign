<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceMonitoringController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Attendance Monitoring Page
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Events available for monitoring
        |--------------------------------------------------------------------------
        |
        | Cancelled events are excluded.
        | Completed events remain available so their attendance can still
        | be reviewed by the administrator.
        |
        */

        $events = Event::query()
            ->where('status', '!=', 'Cancelled')
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->get();


        $selectedEvent = null;

        $attendanceRecords = collect();

        $attendanceCount = 0;

        $presentCount = 0;

        $lateCount = 0;

        $latestAttendance = null;


        /*
        |--------------------------------------------------------------------------
        | Load selected event
        |--------------------------------------------------------------------------
        */

        if ($request->filled('event_id')) {

            $selectedEvent = Event::where(
                'event_id',
                $request->integer('event_id')
            )
            ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Attendance totals
            |--------------------------------------------------------------------------
            */

            $attendanceCount = DB::table('attendance_records')
                ->where(
                    'event_id',
                    $selectedEvent->event_id
                )
                ->count();


            $presentCount = DB::table('attendance_records')
                ->where(
                    'event_id',
                    $selectedEvent->event_id
                )
                ->where(
                    'status',
                    'Present'
                )
                ->count();


            $lateCount = DB::table('attendance_records')
                ->where(
                    'event_id',
                    $selectedEvent->event_id
                )
                ->where(
                    'status',
                    'Late'
                )
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Latest attendance records
            |--------------------------------------------------------------------------
            */

            $attendanceRecords = $this
                ->attendanceQuery(
                    $selectedEvent->event_id
                )
                ->limit(50)
                ->get();


            $latestAttendance =
                $attendanceRecords->first();
        }


        return view(
            'attendance.monitor',
            compact(
                'events',
                'selectedEvent',
                'attendanceRecords',
                'attendanceCount',
                'presentCount',
                'lateCount',
                'latestAttendance'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Live Monitoring Feed
    |--------------------------------------------------------------------------
    |
    | Called every few seconds by the Admin Attendance Monitoring page.
    |
    */

    public function feed($event)
    {
        $event = Event::where(
            'event_id',
            $event
        )
        ->firstOrFail();


        $attendanceCount = DB::table('attendance_records')
            ->where(
                'event_id',
                $event->event_id
            )
            ->count();


        $presentCount = DB::table('attendance_records')
            ->where(
                'event_id',
                $event->event_id
            )
            ->where(
                'status',
                'Present'
            )
            ->count();


        $lateCount = DB::table('attendance_records')
            ->where(
                'event_id',
                $event->event_id
            )
            ->where(
                'status',
                'Late'
            )
            ->count();


        $records = $this
            ->attendanceQuery(
                $event->event_id
            )
            ->limit(50)
            ->get()
            ->map(function ($record) {

                $fullName = trim(
                    $record->first_name
                    . ' '
                    . (
                        $record->middle_name
                            ? $record->middle_name . ' '
                            : ''
                    )
                    . $record->last_name
                );


                return [

                    'attendance_id' =>
                        $record->attendance_id,

                    'student_number' =>
                        $record->student_number,

                    'name' =>
                        $fullName,

                    'initial' =>
                        strtoupper(
                            substr(
                                $record->first_name,
                                0,
                                1
                            )
                        ),

                    'program_code' =>
                        $record->program_code,

                    'year_level' =>
                        $record->year_level,
                    
                    'photo_url' =>
                        $record->photo_path
                            ? asset(
                                'student_photos/'
                                . basename($record->photo_path)
                            )
                            : null,

                    'status' =>
                        $record->status,

                    'time_in' =>
                        $record->time_in
                            ? Carbon::parse(
                                $record->time_in
                            )->format('h:i A')
                            : '—',

                    'scanned_by' =>
                        $record->scanned_by_name
                            ?: 'Unknown',

                ];
            })
            ->values();


        return response()->json([

            'attendance_count' =>
                $attendanceCount,

            'present_count' =>
                $presentCount,

            'late_count' =>
                $lateCount,

            'latest' =>
                $records->first(),

            'records' =>
                $records,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Database Query
    |--------------------------------------------------------------------------
    */

    private function attendanceQuery(
        int $eventId
    ) {

        return DB::table(
                'attendance_records as ar'
            )
            ->join(
                'students as s',
                's.student_id',
                '=',
                'ar.student_id'
            )
            ->leftJoin(
                'users as u',
                'u.user_id',
                '=',
                'ar.scanned_by'
            )
            ->where(
                'ar.event_id',
                $eventId
            )
            ->select([

                'ar.attendance_id',

                'ar.event_id',

                'ar.time_in',

                'ar.status',

                'ar.scanned_by',

                's.student_id',

                's.student_number',

                's.first_name',

                's.middle_name',

                's.last_name',

                's.program_code',

                's.year_level',

                's.photo_path',

                'u.name as scanned_by_name',

            ])
            ->orderByDesc(
                'ar.time_in'
            );
    }
}