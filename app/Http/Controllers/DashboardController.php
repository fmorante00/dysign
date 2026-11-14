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

        abort_unless(
            $user && $user->role?->role_name === 'Department Staff',
            403
        );

        // Never expose another department's events when a staff account has
        // not yet been assigned to a department.
        $departmentId = $user->department_id;
        $departmentName = $user->department?->department_name
            ?? 'No Department Assigned';

        $departmentEvents = Event::query();

        if ($departmentId) {
            $departmentEvents->where('department_id', $departmentId);
        } else {
            $departmentEvents->whereRaw('1 = 0');
        }

        $totalEvents = (clone $departmentEvents)->count();
        $upcomingEventsCount = (clone $departmentEvents)
            ->where('status', 'Upcoming')->count();
        $ongoingEventsCount = (clone $departmentEvents)
            ->where('status', 'Ongoing')->count();
        $completedEventsCount = (clone $departmentEvents)
            ->where('status', 'Completed')->count();
        $cancelledEventsCount = (clone $departmentEvents)
            ->whereIn('status', ['Cancelled', 'Canceled'])->count();

        // Keep the event previews independent of the KPI totals: five rows
        // must never be mistaken for the department's overall event count.
        $ongoingEvents = (clone $departmentEvents)
            ->where('status', 'Ongoing')
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        $upcomingEvents = (clone $departmentEvents)
            ->where('status', 'Upcoming')
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $recentEvents = (clone $departmentEvents)
            ->orderByDesc('updated_at')
            ->orderByDesc('event_date')
            ->take(5)
            ->get();

        $recentCompletedEvents = (clone $departmentEvents)
            ->where('status', 'Completed')
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->take(3)
            ->get();

        // Department-scoped source for the interactive calendar and its agenda.
        // Status KPI counts remain independent of the five-event agenda preview.
        $calendarEvents = (clone $departmentEvents)
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        return view('dashboard.department', compact(
            'departmentName',
            'totalEvents',
            'upcomingEventsCount',
            'ongoingEventsCount',
            'completedEventsCount',
            'cancelledEventsCount',
            'ongoingEvents',
            'upcomingEvents',
            'recentEvents',
            'recentCompletedEvents',
            'calendarEvents'
        ));
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



    /*
    |--------------------------------------------------------------------------
    | Student Account Dashboard
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        $user = auth()->user();

        // Only Student Accounts may use this dashboard method.
        abort_unless(
            $user && $user->role?->role_name === 'Student Account',
            403
        );

        // Use the linked student record, never a student ID from the URL.
        $student = $user->student;

        abort_unless(
            $student && $student->status === 'Active',
            403,
            'No active student record is linked to this account.'
        );

        $totalAttendanceRecords = $student->attendanceRecords()->count();

        $recentAttendance = $student->attendanceRecords()
            ->with('event')
            ->orderByDesc('time_in')
            ->take(5)
            ->get();

        return view('dashboard.student', compact(
            'student',
            'totalAttendanceRecords',
            'recentAttendance'
        ));
    }

}