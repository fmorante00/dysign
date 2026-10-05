<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Personnel;
use App\Models\Role;
use App\Models\Event;
use App\Models\EventAssignment;
use App\Models\AttendanceRecord;

class DashboardController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Administrator Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $totalUsers = User::count();

        $activeUsers = User::where(
            'status',
            'Active'
        )->count();

        $totalPersonnel = Personnel::count();

        $totalRoles = Role::count();

        $roleDistribution = Role::withCount('users')
            ->get();

        $recentPersonnel = Personnel::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view(
            'dashboard.admin',
            compact(
                'totalUsers',
                'activeUsers',
                'totalPersonnel',
                'totalRoles',
                'roleDistribution',
                'recentPersonnel'
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Department Staff Dashboard
    |--------------------------------------------------------------------------
    */

    public function departmentStaff()
    {

        $user = auth()->user();

        $departmentId = $user->department_id;

        $departmentName = $user->department->department_name
            ?? 'No Department Assigned';


        /*
        |--------------------------------------------------------------------------
        | Department Event Counts
        |--------------------------------------------------------------------------
        */

        $totalEvents = Event::where(
            'department_id',
            $departmentId
        )->count();


        $upcomingEventsCount = Event::where(
            'department_id',
            $departmentId
        )
        ->where('status', 'Upcoming')
        ->count();


        $ongoingEventsCount = Event::where(
            'department_id',
            $departmentId
        )
        ->where('status', 'Ongoing')
        ->count();


        $completedEventsCount = Event::where(
            'department_id',
            $departmentId
        )
        ->where('status', 'Completed')
        ->count();


        /*
        |--------------------------------------------------------------------------
        | Upcoming Department Events
        |--------------------------------------------------------------------------
        */

        $upcomingEvents = Event::where(
            'department_id',
            $departmentId
        )
        ->where('status', 'Upcoming')
        ->whereDate(
            'event_date',
            '>=',
            now()->toDateString()
        )
        ->orderBy('event_date')
        ->orderBy('start_time')
        ->take(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Department Events
        |--------------------------------------------------------------------------
        */

        $recentEvents = Event::where(
            'department_id',
            $departmentId
        )
        ->latest()
        ->take(5)
        ->get();


        return view(
            'dashboard.department',
            compact(
                'departmentName',
                'totalEvents',
                'upcomingEventsCount',
                'ongoingEventsCount',
                'completedEventsCount',
                'upcomingEvents',
                'recentEvents'
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel Dashboard
    |--------------------------------------------------------------------------
    */

    public function attendancePersonnel()
    {

        $user = auth()->user();

        $personnel = $user->personnel;


        /*
        |--------------------------------------------------------------------------
        | Make Sure User Has Personnel Record
        |--------------------------------------------------------------------------
        */

        if (!$personnel) {

            abort(
                403,
                'No personnel profile is linked to this account.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Assigned Event IDs
        |--------------------------------------------------------------------------
        */

        $assignedEventIds = EventAssignment::where(
            'personnel_id',
            $personnel->personnel_id
        )
        ->pluck('event_id');


        /*
        |--------------------------------------------------------------------------
        | Total Assigned Events
        |--------------------------------------------------------------------------
        */

        $totalAssignedEvents = Event::whereIn(
            'event_id',
            $assignedEventIds
        )
        ->count();


        /*
        |--------------------------------------------------------------------------
        | Current / Next Assigned Event
        |--------------------------------------------------------------------------
        */

        $currentEvent = Event::with('department')
            ->whereIn(
                'event_id',
                $assignedEventIds
            )
            ->where('status', 'Ongoing')
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->first();


        /*
        | If there is no ongoing event,
        | show the next upcoming assigned event.
        */

        if (!$currentEvent) {

            $currentEvent = Event::with('department')
                ->whereIn(
                    'event_id',
                    $assignedEventIds
                )
                ->where('status', 'Upcoming')
                ->whereDate(
                    'event_date',
                    '>=',
                    now()->toDateString()
                )
                ->orderBy('event_date')
                ->orderBy('start_time')
                ->first();

        }


        /*
        |--------------------------------------------------------------------------
        | Upcoming Assigned Events
        |--------------------------------------------------------------------------
        */

        $upcomingEvents = Event::with('department')
            ->whereIn(
                'event_id',
                $assignedEventIds
            )
            ->where('status', 'Upcoming')
            ->whereDate(
                'event_date',
                '>=',
                now()->toDateString()
            )
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Upcoming Event Count
        |--------------------------------------------------------------------------
        */

        $upcomingEventsCount = Event::whereIn(
            'event_id',
            $assignedEventIds
        )
        ->where('status', 'Upcoming')
        ->whereDate(
            'event_date',
            '>=',
            now()->toDateString()
        )
        ->count();


        /*
        |--------------------------------------------------------------------------
        | Total Attendance Scans
        |--------------------------------------------------------------------------
        */

        $totalScanned = AttendanceRecord::whereIn(
            'event_id',
            $assignedEventIds
        )
        ->count();


        /*
        |--------------------------------------------------------------------------
        | Today's Attendance Scans
        |--------------------------------------------------------------------------
        */

        $todayScanned = AttendanceRecord::whereIn(
            'event_id',
            $assignedEventIds
        )
        ->whereDate(
            'time_in',
            now()->toDateString()
        )
        ->count();


        /*
        |--------------------------------------------------------------------------
        | Current Event Attendance Count
        |--------------------------------------------------------------------------
        */

        $currentEventScanned = 0;

        if ($currentEvent) {

            $currentEventScanned = AttendanceRecord::where(
                'event_id',
                $currentEvent->event_id
            )
            ->count();

        }


        /*
        |--------------------------------------------------------------------------
        | Recent RFID Scans
        |--------------------------------------------------------------------------
        */

        $recentAttendance = AttendanceRecord::with([
            'student',
            'event'
        ])
        ->whereIn(
            'event_id',
            $assignedEventIds
        )
        ->orderByDesc('time_in')
        ->take(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.attendance',
            compact(
                'personnel',
                'currentEvent',
                'totalAssignedEvents',
                'upcomingEventsCount',
                'totalScanned',
                'todayScanned',
                'currentEventScanned',
                'upcomingEvents',
                'recentAttendance'
            )
        );

    }

}