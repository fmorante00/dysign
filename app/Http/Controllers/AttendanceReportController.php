<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filter Values
        |--------------------------------------------------------------------------
        */

        $selectedEvent = $request->get('event_id');

        $selectedStatus = $request->get(
            'status',
            'all'
        );

        $generated = $request->boolean(
            'generated'
        );


        /*
        |--------------------------------------------------------------------------
        | Events
        |--------------------------------------------------------------------------
        */

        $events = Event::query()
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->get([
                'event_id',
                'event_name',
                'event_date',
                'status',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Base Attendance Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = DB::table(
                'attendance_records as ar'
            )

            ->join(
                'students as s',
                's.student_id',
                '=',
                'ar.student_id'
            )

            ->join(
                'events as e',
                'e.event_id',
                '=',
                'ar.event_id'
            )

            ->leftJoin(
                'users as u',
                'u.user_id',
                '=',
                'ar.scanned_by'
            );


        /*
        |--------------------------------------------------------------------------
        | Apply Filters
        |--------------------------------------------------------------------------
        */

        if (
            !empty($selectedEvent)
            && $selectedEvent !== 'all'
        ) {

            $baseQuery->where(
                'ar.event_id',
                $selectedEvent
            );

        }


        if (
            in_array(
                $selectedStatus,
                [
                    'Present',
                    'Late',
                ],
                true
            )
        ) {

            $baseQuery->where(
                'ar.status',
                $selectedStatus
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        |
        | Summary follows the currently selected filters.
        |
        */

        $summaryQuery =
            clone $baseQuery;


        $summaryRows =
            $summaryQuery
                ->select([
                    'ar.event_id',
                    'ar.status',
                ])
                ->get();


        $totalEvents =
            $summaryRows
                ->pluck('event_id')
                ->unique()
                ->count();


        $totalPresent =
            $summaryRows
                ->where(
                    'status',
                    'Present'
                )
                ->count();


        $totalLate =
            $summaryRows
                ->where(
                    'status',
                    'Late'
                )
                ->count();


        $totalAttendanceEntries =
            $summaryRows->count();


        /*
        |--------------------------------------------------------------------------
        | On-Time Rate
        |--------------------------------------------------------------------------
        */

        $onTimeRate =
            $totalAttendanceEntries > 0
                ? round(
                    (
                        $totalPresent
                        / $totalAttendanceEntries
                    ) * 100,
                    1
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | Report Records
        |--------------------------------------------------------------------------
        |
        | Records are shown only after Generate Report is clicked.
        |
        */

        if ($generated) {

            $records =
                $baseQuery

                    ->select([
                        'ar.attendance_id',
                        'ar.event_id',
                        'ar.student_id',
                        'ar.rfid_identifier',
                        'ar.time_in',
                        'ar.status',

                        's.student_number',
                        's.first_name',
                        's.middle_name',
                        's.last_name',
                        's.program_code',
                        's.program_name',
                        's.year_level',
                        's.college',
                        's.photo_path',

                        'e.event_name',
                        'e.event_date',
                        'e.start_time',
                        'e.end_time',
                        'e.location',
                        'e.status as event_status',

                        'u.name as scanned_by_name',
                    ])

                    ->orderByDesc(
                        'e.event_date'
                    )

                    ->orderByDesc(
                        'ar.time_in'
                    )

                    ->paginate(20)

                    ->withQueryString();

        } else {

            $records = null;

        }


        /*
        |--------------------------------------------------------------------------
        | Selected Event Information
        |--------------------------------------------------------------------------
        */

        $selectedEventData = null;

        if (
            !empty($selectedEvent)
            && $selectedEvent !== 'all'
        ) {

            $selectedEventData =
                Event::where(
                    'event_id',
                    $selectedEvent
                )
                ->first();

        }


        return view(
            'reports.attendance',
            compact(
                'events',
                'records',
                'generated',
                'selectedEvent',
                'selectedStatus',
                'selectedEventData',
                'totalEvents',
                'totalPresent',
                'totalLate',
                'totalAttendanceEntries',
                'onTimeRate'
            )
        );
    }
}