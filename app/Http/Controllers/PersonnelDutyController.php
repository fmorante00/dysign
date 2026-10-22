<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Personnel;
use App\Models\PersonnelDuty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PersonnelDutyController extends Controller
{
    private const TIMEZONE = 'Asia/Manila';


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel - My Duties
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Synchronize Event Statuses
        |--------------------------------------------------------------------------
        */

        Event::syncScheduledStatuses();


        /*
        |--------------------------------------------------------------------------
        | Create Missing Duty Records
        |--------------------------------------------------------------------------
        */

        $this->syncPersonnelDutyRecords(
            $personnel->personnel_id
        );


        /*
        |--------------------------------------------------------------------------
        | Synchronize Duty Statuses
        |--------------------------------------------------------------------------
        */

        $this->syncPersonnelDutyStatuses(
            $personnel->personnel_id
        );


        /*
        |--------------------------------------------------------------------------
        | Retrieve Assigned Duties
        |--------------------------------------------------------------------------
        */

        $duties =
            DB::table(
                'event_assignments as assignment'
            )
            ->join(
                'events',
                'events.event_id',
                '=',
                'assignment.event_id'
            )
            ->leftJoin(
                'personnel_duties as duty',
                'duty.assignment_id',
                '=',
                'assignment.assignment_id'
            )
            ->where(
                'assignment.personnel_id',
                $personnel->personnel_id
            )
            ->where(
                'events.status',
                '!=',
                'Cancelled'
            )
            ->select([
                'assignment.assignment_id',

                'events.event_id',
                'events.event_name',
                'events.event_date',
                'events.start_time',
                'events.end_time',
                'events.location',
                'events.status as event_status',

                'duty.duty_id',
                'duty.check_in_at',
                'duty.check_out_at',
                'duty.duty_status',
                'duty.completed_minutes',
            ])
            ->orderByDesc(
                'events.event_date'
            )
            ->orderByDesc(
                'events.start_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $duties->count(),

            'assigned' =>
                $duties
                    ->where(
                        'duty_status',
                        'Assigned'
                    )
                    ->count(),

            'on_duty' =>
                $duties
                    ->where(
                        'duty_status',
                        'On Duty'
                    )
                    ->count(),

            'completed' =>
                $duties
                    ->where(
                        'duty_status',
                        'Completed'
                    )
                    ->count(),

            'missed' =>
                $duties
                    ->where(
                        'duty_status',
                        'Missed'
                    )
                    ->count(),

            'incomplete' =>
                $duties
                    ->where(
                        'duty_status',
                        'Incomplete'
                    )
                    ->count(),

        ];


        return view(
            'personnel-duties.index',
            compact(
                'personnel',
                'duties',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel - Check In
    |--------------------------------------------------------------------------
    */

    public function checkIn(
        string $assignment
    ) {

        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Verify Assignment
        |--------------------------------------------------------------------------
        */

        $assignmentRecord =
            $this->ownedAssignment(
                $assignment,
                $personnel->personnel_id
            );


        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        $event =
            Event::where(
                'event_id',
                $assignmentRecord->event_id
            )
            ->firstOrFail();


        $event->syncScheduledStatus();

        $event->refresh();


        /*
        |--------------------------------------------------------------------------
        | Check-In Allowed Only During Ongoing Event
        |--------------------------------------------------------------------------
        */

        if (
            $event->status
            !== 'Ongoing'
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'Duty check-in is only available while the event is ongoing.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Duty Record
        |--------------------------------------------------------------------------
        */

        $duty =
            PersonnelDuty::firstOrCreate(
                [
                    'assignment_id' =>
                        $assignmentRecord
                            ->assignment_id,
                ],
                [
                    'event_id' =>
                        $assignmentRecord
                            ->event_id,

                    'personnel_id' =>
                        $personnel
                            ->personnel_id,

                    'duty_status' =>
                        'Assigned',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Check-In
        |--------------------------------------------------------------------------
        */

        if (
            $duty->check_in_at
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'You have already checked in for this duty.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Save Check-In
        |--------------------------------------------------------------------------
        */

        $duty->update([

            'check_in_at' =>
                Carbon::now(
                    self::TIMEZONE
                ),

            'duty_status' =>
                'On Duty',

        ]);


        return redirect()
            ->route(
                'personnel-duties.index'
            )
            ->with(
                'success',
                'Duty check-in recorded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel - Check Out
    |--------------------------------------------------------------------------
    */

    public function checkOut(
        string $assignment
    ) {

        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Verify Assignment
        |--------------------------------------------------------------------------
        */

        $assignmentRecord =
            $this->ownedAssignment(
                $assignment,
                $personnel->personnel_id
            );


        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        $event =
            Event::where(
                'event_id',
                $assignmentRecord->event_id
            )
            ->firstOrFail();


        $event->syncScheduledStatus();

        $event->refresh();


        /*
        |--------------------------------------------------------------------------
        | Check-Out Allowed Only During Ongoing Event
        |--------------------------------------------------------------------------
        */

        if (
            $event->status
            !== 'Ongoing'
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'Duty check-out is only available while the event is ongoing.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Duty
        |--------------------------------------------------------------------------
        */

        $duty =
            PersonnelDuty::where(
                'assignment_id',
                $assignmentRecord
                    ->assignment_id
            )
            ->where(
                'personnel_id',
                $personnel
                    ->personnel_id
            )
            ->first();


        if (
            !$duty
            ||
            !$duty->check_in_at
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'You must check in before checking out.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Check-Out
        |--------------------------------------------------------------------------
        */

        if (
            $duty->check_out_at
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'You have already checked out from this duty.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Record Check-Out
        |--------------------------------------------------------------------------
        */

        $checkOut =
            Carbon::now(
                self::TIMEZONE
            );


        $checkIn =
            Carbon::parse(
                $duty->check_in_at,
                self::TIMEZONE
            );


        $completedMinutes =
            max(
                0,
                $checkIn->diffInMinutes(
                    $checkOut
                )
            );


        $duty->update([

            'check_out_at' =>
                $checkOut,

            'completed_minutes' =>
                $completedMinutes,

            'duty_status' =>
                'Completed',

        ]);


        return redirect()
            ->route(
                'personnel-duties.index'
            )
            ->with(
                'success',
                'Duty check-out recorded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Department Staff - Duty Monitoring
    |--------------------------------------------------------------------------
    */

    public function staffIndex(
        Request $request
    ) {

        $user =
            $this->departmentStaffUser();


        Event::syncScheduledStatuses();


        /*
        |--------------------------------------------------------------------------
        | Create Missing Duty Records
        |--------------------------------------------------------------------------
        */

        $this->syncDepartmentDutyRecords(
            $user->department_id
        );


        /*
        |--------------------------------------------------------------------------
        | Synchronize Statuses
        |--------------------------------------------------------------------------
        */

        $this->syncDepartmentDutyStatuses(
            $user->department_id
        );


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'event' => [
                'nullable',
                'integer',
            ],

            'status' => [
                'nullable',
                'in:Assigned,On Duty,Completed,Missed,Incomplete',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Events For Filter
        |--------------------------------------------------------------------------
        */

        $events =
            Event::where(
                'department_id',
                $user->department_id
            )
            ->where(
                'status',
                '!=',
                'Cancelled'
            )
            ->orderByDesc(
                'event_date'
            )
            ->orderByDesc(
                'start_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Duty Query
        |--------------------------------------------------------------------------
        */

        $query =
            DB::table(
                'personnel_duties as duty'
            )
            ->join(
                'event_assignments as assignment',
                'assignment.assignment_id',
                '=',
                'duty.assignment_id'
            )
            ->join(
                'events',
                'events.event_id',
                '=',
                'duty.event_id'
            )
            ->join(
                'personnel',
                'personnel.personnel_id',
                '=',
                'duty.personnel_id'
            )
            ->where(
                'events.department_id',
                $user->department_id
            )
            ->where(
                'events.status',
                '!=',
                'Cancelled'
            );


        /*
        |--------------------------------------------------------------------------
        | Event Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'event'
            )
        ) {

            $query->where(
                'events.event_id',
                $request->integer(
                    'event'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Duty Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'status'
            )
        ) {

            $query->where(
                'duty.duty_status',
                $request->input(
                    'status'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Duty Records
        |--------------------------------------------------------------------------
        */

        $duties =
            $query
                ->select([

                    'duty.duty_id',
                    'duty.assignment_id',
                    'duty.check_in_at',
                    'duty.check_out_at',
                    'duty.duty_status',
                    'duty.completed_minutes',

                    'events.event_id',
                    'events.event_name',
                    'events.event_date',
                    'events.start_time',
                    'events.end_time',
                    'events.location',
                    'events.status as event_status',

                    'personnel.personnel_id',
                    'personnel.first_name',
                    'personnel.last_name',
                    'personnel.position',

                ])
                ->orderByDesc(
                    'events.event_date'
                )
                ->orderByDesc(
                    'events.start_time'
                )
                ->orderBy(
                    'personnel.last_name'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $duties->count(),

            'assigned' =>
                $duties
                    ->where(
                        'duty_status',
                        'Assigned'
                    )
                    ->count(),

            'on_duty' =>
                $duties
                    ->where(
                        'duty_status',
                        'On Duty'
                    )
                    ->count(),

            'completed' =>
                $duties
                    ->where(
                        'duty_status',
                        'Completed'
                    )
                    ->count(),

            'missed' =>
                $duties
                    ->where(
                        'duty_status',
                        'Missed'
                    )
                    ->count(),

            'incomplete' =>
                $duties
                    ->where(
                        'duty_status',
                        'Incomplete'
                    )
                    ->count(),

        ];


        return view(
            'personnel-duties.staff',
            compact(
                'events',
                'duties',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Missing Duty Records - Personnel
    |--------------------------------------------------------------------------
    */

    private function syncPersonnelDutyRecords(
        int $personnelId
    ): void {

        $assignments =
            DB::table(
                'event_assignments as assignment'
            )
            ->join(
                'events',
                'events.event_id',
                '=',
                'assignment.event_id'
            )
            ->where(
                'assignment.personnel_id',
                $personnelId
            )
            ->where(
                'events.status',
                '!=',
                'Cancelled'
            )
            ->select([
                'assignment.assignment_id',
                'assignment.event_id',
                'assignment.personnel_id',
            ])
            ->get();


        foreach (
            $assignments
            as $assignment
        ) {

            PersonnelDuty::firstOrCreate(

                [
                    'assignment_id' =>
                        $assignment
                            ->assignment_id,
                ],

                [
                    'event_id' =>
                        $assignment
                            ->event_id,

                    'personnel_id' =>
                        $assignment
                            ->personnel_id,

                    'duty_status' =>
                        'Assigned',
                ]

            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Create Missing Duty Records - Department
    |--------------------------------------------------------------------------
    */

    private function syncDepartmentDutyRecords(
        int $departmentId
    ): void {

        $assignments =
            DB::table(
                'event_assignments as assignment'
            )
            ->join(
                'events',
                'events.event_id',
                '=',
                'assignment.event_id'
            )
            ->where(
                'events.department_id',
                $departmentId
            )
            ->where(
                'events.status',
                '!=',
                'Cancelled'
            )
            ->select([
                'assignment.assignment_id',
                'assignment.event_id',
                'assignment.personnel_id',
            ])
            ->get();


        foreach (
            $assignments
            as $assignment
        ) {

            PersonnelDuty::firstOrCreate(

                [
                    'assignment_id' =>
                        $assignment
                            ->assignment_id,
                ],

                [
                    'event_id' =>
                        $assignment
                            ->event_id,

                    'personnel_id' =>
                        $assignment
                            ->personnel_id,

                    'duty_status' =>
                        'Assigned',
                ]

            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Synchronize Personnel Duty Statuses
    |--------------------------------------------------------------------------
    */

    private function syncPersonnelDutyStatuses(
        int $personnelId
    ): void {

        $duties =
            PersonnelDuty::where(
                'personnel_id',
                $personnelId
            )
            ->get();


        foreach (
            $duties
            as $duty
        ) {

            $this->syncDutyStatus(
                $duty
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Synchronize Department Duty Statuses
    |--------------------------------------------------------------------------
    */

    private function syncDepartmentDutyStatuses(
        int $departmentId
    ): void {

        $duties =
            PersonnelDuty::whereHas(
                'event',
                function ($query) use (
                    $departmentId
                ) {

                    $query->where(
                        'department_id',
                        $departmentId
                    );

                }
            )
            ->get();


        foreach (
            $duties
            as $duty
        ) {

            $this->syncDutyStatus(
                $duty
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Automatic Duty Status
    |--------------------------------------------------------------------------
    */

    private function syncDutyStatus(
        PersonnelDuty $duty
    ): void {

        $event =
            Event::where(
                'event_id',
                $duty->event_id
            )
            ->first();


        if (
            !$event
        ) {

            return;
        }


        $event->syncScheduledStatus();

        $event->refresh();


        /*
        |--------------------------------------------------------------------------
        | Cancelled Events
        |--------------------------------------------------------------------------
        */

        if (
            $event->status
            === 'Cancelled'
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Completed Duty
        |--------------------------------------------------------------------------
        */

        if (
            $duty->check_in_at
            &&
            $duty->check_out_at
        ) {

            $checkIn =
                Carbon::parse(
                    $duty->check_in_at,
                    self::TIMEZONE
                );


            $checkOut =
                Carbon::parse(
                    $duty->check_out_at,
                    self::TIMEZONE
                );


            $minutes =
                max(
                    0,
                    $checkIn->diffInMinutes(
                        $checkOut
                    )
                );


            $duty->update([

                'duty_status' =>
                    'Completed',

                'completed_minutes' =>
                    $minutes,

            ]);


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Event Already Completed
        |--------------------------------------------------------------------------
        */

        if (
            $event->status
            === 'Completed'
        ) {

            /*
            |--------------------------------------------------------------------------
            | Never Checked In
            |--------------------------------------------------------------------------
            */

            if (
                !$duty->check_in_at
            ) {

                $duty->update([

                    'duty_status' =>
                        'Missed',

                    'completed_minutes' =>
                        null,

                ]);


                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Checked In But Never Checked Out
            |--------------------------------------------------------------------------
            */

            $duty->update([

                'duty_status' =>
                    'Incomplete',

                'completed_minutes' =>
                    null,

            ]);


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Personnel Currently On Duty
        |--------------------------------------------------------------------------
        */

        if (
            $duty->check_in_at
            &&
            !$duty->check_out_at
        ) {

            $duty->update([

                'duty_status' =>
                    'On Duty',

            ]);


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Upcoming / Waiting
        |--------------------------------------------------------------------------
        */

        $duty->update([

            'duty_status' =>
                'Assigned',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Owned Assignment
    |--------------------------------------------------------------------------
    */

    private function ownedAssignment(
        string $assignmentId,
        int $personnelId
    ): object {

        $assignment =
            DB::table(
                'event_assignments'
            )
            ->where(
                'assignment_id',
                $assignmentId
            )
            ->where(
                'personnel_id',
                $personnelId
            )
            ->first();


        if (
            !$assignment
        ) {

            abort(403);
        }


        return $assignment;
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel Authorization
    |--------------------------------------------------------------------------
    */

    private function attendancePersonnel(): Personnel
    {
        $user =
            auth()->user();


        if (
            !$user
            ||
            (
                $user->role->role_name
                ?? null
            )
            !== 'Attendance Personnel'
        ) {

            abort(403);
        }


        $personnel =
            $user->personnel;


        if (
            !$personnel
        ) {

            abort(
                403,
                'No personnel profile is linked to this account.'
            );
        }


        return $personnel;
    }


    /*
    |--------------------------------------------------------------------------
    | Department Staff Authorization
    |--------------------------------------------------------------------------
    */

    private function departmentStaffUser()
    {
        $user =
            auth()->user();


        if (
            !$user
            ||
            (
                $user->role->role_name
                ?? null
            )
            !== 'Department Staff'
        ) {

            abort(403);
        }


        if (
            !$user->department_id
        ) {

            abort(
                403,
                'Your account is not linked to a department.'
            );
        }


        return $user;
    }
}