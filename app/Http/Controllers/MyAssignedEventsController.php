<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\Personnel;

class MyAssignedEventsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | My Assigned Events
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Synchronize Event Statuses
        |--------------------------------------------------------------------------
        |
        | Before loading assigned events, automatically update event statuses
        | based on their scheduled date, start time, and end time.
        |
        */

        Event::syncScheduledStatuses();


        /*
        |--------------------------------------------------------------------------
        | Validate Attendance Personnel
        |--------------------------------------------------------------------------
        */

        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Assigned Events
        |--------------------------------------------------------------------------
        |
        | Priority:
        |
        | 1. Ongoing
        | 2. Upcoming
        | 3. Completed
        | 4. Cancelled
        |
        */

        $events =
            Event::whereHas(
                'assignments',
                function ($query) use ($personnel) {

                    $query->where(
                        'personnel_id',
                        $personnel->personnel_id
                    );

                }
            )
            ->with(
                'department'
            )
            ->orderByRaw("
                CASE status
                    WHEN 'Ongoing' THEN 1
                    WHEN 'Upcoming' THEN 2
                    WHEN 'Completed' THEN 3
                    WHEN 'Cancelled' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy(
                'event_date'
            )
            ->orderBy(
                'start_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Attendance Counts
        |--------------------------------------------------------------------------
        |
        | Attendance counts are calculated directly from attendance_records.
        |
        */

        $eventIds =
            $events
                ->pluck(
                    'event_id'
                )
                ->values();


        $attendanceCounts =
            collect();


        if (
            $eventIds->isNotEmpty()
        ) {

            $attendanceCounts =
                AttendanceRecord::selectRaw(
                    'event_id, COUNT(*) as total'
                )
                ->whereIn(
                    'event_id',
                    $eventIds
                )
                ->groupBy(
                    'event_id'
                )
                ->pluck(
                    'total',
                    'event_id'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Add Display Information
        |--------------------------------------------------------------------------
        */

        $events->each(
            function ($event) use ($attendanceCounts) {

                /*
                |--------------------------------------------------------------------------
                | Attendance Count
                |--------------------------------------------------------------------------
                */

                $event->setAttribute(
                    'attendance_count',
                    (int) (
                        $attendanceCounts[
                            $event->event_id
                        ]
                        ?? 0
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | Scanner Availability
                |--------------------------------------------------------------------------
                */

                $event->setAttribute(
                    'can_scan',
                    $this->canScan(
                        $event
                    )
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Display Assigned Events
        |--------------------------------------------------------------------------
        */

        return view(
            'my-events.index',
            compact(
                'events'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned Event Details
    |--------------------------------------------------------------------------
    */

    public function show($event)
    {
        /*
        |--------------------------------------------------------------------------
        | Synchronize Event Statuses
        |--------------------------------------------------------------------------
        */

        Event::syncScheduledStatuses();


        /*
        |--------------------------------------------------------------------------
        | Validate Attendance Personnel
        |--------------------------------------------------------------------------
        */

        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Event Must Be Assigned To Logged-In Personnel
        |--------------------------------------------------------------------------
        */

        $event =
            $this->assignedEvent(
                $event,
                $personnel
            );


        /*
        |--------------------------------------------------------------------------
        | Synchronize Selected Event
        |--------------------------------------------------------------------------
        |
        | This provides an additional check in case the event schedule changed
        | immediately before this page was opened.
        |
        */

        $event->syncScheduledStatus();


        /*
        |--------------------------------------------------------------------------
        | Refresh Event Data
        |--------------------------------------------------------------------------
        */

        $event->refresh();

        $event->load(
            'department'
        );


        /*
        |--------------------------------------------------------------------------
        | Attendance Count
        |--------------------------------------------------------------------------
        */

        $attendanceCount =
            AttendanceRecord::where(
                'event_id',
                $event->event_id
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Scanner Availability
        |--------------------------------------------------------------------------
        */

        $canScan =
            $this->canScan(
                $event
            );


        /*
        |--------------------------------------------------------------------------
        | Display Event
        |--------------------------------------------------------------------------
        */

        return view(
            'my-events.show',
            compact(
                'event',
                'attendanceCount',
                'canScan'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Attendance Personnel Account
    |--------------------------------------------------------------------------
    |
    | Ensures that only an authenticated Attendance Personnel account with
    | a linked personnel profile can access this module.
    |
    */

    private function attendancePersonnel(): Personnel
    {
        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Authentication Check
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            abort(
                403
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Role Check
        |--------------------------------------------------------------------------
        */

        if (
            (
                $user->role->role_name
                ?? null
            )
            !==
            'Attendance Personnel'
        ) {

            abort(
                403
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Personnel Profile
        |--------------------------------------------------------------------------
        */

        $personnel =
            $user->personnel;


        if (!$personnel) {

            abort(
                403,
                'No personnel profile is linked to this account.'
            );

        }


        return $personnel;
    }


    /*
    |--------------------------------------------------------------------------
    | Get One Assigned Event
    |--------------------------------------------------------------------------
    */

    private function assignedEvent(
        $event,
        Personnel $personnel
    ): Event {

        return Event::where(
            'event_id',
            $event
        )
        ->whereHas(
            'assignments',
            function ($query) use ($personnel) {

                $query->where(
                    'personnel_id',
                    $personnel->personnel_id
                );

            }
        )
        ->with(
            'department'
        )
        ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | Scanner Availability
    |--------------------------------------------------------------------------
    |
    | Scanner access is available for upcoming and ongoing assignments.
    | Completed and cancelled events cannot open an active scanner session.
    |
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
}