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
        $personnel = $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Assigned Events
        |--------------------------------------------------------------------------
        |
        | Priority:
        | 1. Ongoing
        | 2. Upcoming
        | 3. Completed
        | 4. Cancelled
        |
        */

        $events = Event::whereHas(
            'assignments',
            function ($query) use ($personnel) {

                $query->where(
                    'personnel_id',
                    $personnel->personnel_id
                );

            }
        )
        ->with('department')
        ->orderByRaw("
            CASE status
                WHEN 'Ongoing' THEN 1
                WHEN 'Upcoming' THEN 2
                WHEN 'Completed' THEN 3
                WHEN 'Cancelled' THEN 4
                ELSE 5
            END
        ")
        ->orderBy('event_date')
        ->orderBy('start_time')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Attendance Counts
        |--------------------------------------------------------------------------
        |
        | We calculate attendance counts directly from attendance_records so
        | this does not depend on an Event attendance relationship.
        |
        */

        $eventIds = $events
            ->pluck('event_id')
            ->values();


        $attendanceCounts = collect();


        if ($eventIds->isNotEmpty()) {

            $attendanceCounts = AttendanceRecord::selectRaw(
                'event_id, COUNT(*) as total'
            )
            ->whereIn(
                'event_id',
                $eventIds
            )
            ->groupBy('event_id')
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

        $events->each(function ($event) use ($attendanceCounts) {

            $event->setAttribute(
                'attendance_count',
                (int) (
                    $attendanceCounts[
                        $event->event_id
                    ] ?? 0
                )
            );


            $event->setAttribute(
                'can_scan',
                $this->canScan($event)
            );

        });


        return view(
            'my-events.index',
            compact('events')
        );
    }





    /*
    |--------------------------------------------------------------------------
    | Assigned Event Details
    |--------------------------------------------------------------------------
    */

    public function show($event)
    {
        $personnel = $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Event Must Be Assigned To Logged-In Personnel
        |--------------------------------------------------------------------------
        */

        $event = $this->assignedEvent(
            $event,
            $personnel
        );


        /*
        |--------------------------------------------------------------------------
        | Attendance Count
        |--------------------------------------------------------------------------
        */

        $attendanceCount = AttendanceRecord::where(
            'event_id',
            $event->event_id
        )
        ->count();


        /*
        |--------------------------------------------------------------------------
        | Scanner Availability
        |--------------------------------------------------------------------------
        */

        $canScan = $this->canScan($event);


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
    | This protects the controller even before route-level middleware is
    | cleaned up.
    |
    */

    private function attendancePersonnel(): Personnel
    {
        $user = auth()->user();


        if (!$user) {

            abort(403);

        }


        if (
            ($user->role->role_name ?? null)
            !==
            'Attendance Personnel'
        ) {

            abort(403);

        }


        $personnel = $user->personnel;


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
        ->with('department')
        ->firstOrFail();
    }





    /*
    |--------------------------------------------------------------------------
    | Scanner Availability
    |--------------------------------------------------------------------------
    */

    private function canScan(Event $event): bool
    {
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