<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Personnel;
use App\Models\PersonnelAvailability;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventPersonnelAssignmentController extends Controller
{
    private const TIMEZONE = 'Asia/Manila';


    /*
    |--------------------------------------------------------------------------
    | Event Selection
    |--------------------------------------------------------------------------
    */

    public function events()
    {
        $user =
            $this->departmentStaffUser();


        Event::syncScheduledStatuses();


        $events =
            Event::with('department')
                ->withCount('assignments')
                ->where(
                    'department_id',
                    $user->department_id
                )
                ->where(
                    'status',
                    'Upcoming'
                )
                ->orderBy(
                    'event_date'
                )
                ->orderBy(
                    'start_time'
                )
                ->get();


        return view(
            'events.personnel-assignment-events',
            compact(
                'events'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assignment Page
    |--------------------------------------------------------------------------
    */

    public function index(
        string $event
    ) {

        $user =
            $this->departmentStaffUser();


        $event =
            $this->accessibleEvent(
                $event
            );


        /*
        |--------------------------------------------------------------------------
        | Currently Assigned Personnel
        |--------------------------------------------------------------------------
        */

        $assignedIds =
            DB::table(
                'event_assignments'
            )
            ->where(
                'event_id',
                $event->event_id
            )
            ->pluck(
                'personnel_id'
            )
            ->map(
                fn ($id) =>
                    (int) $id
            )
            ->all();


        /*
        |--------------------------------------------------------------------------
        | Eligible Attendance Personnel
        |--------------------------------------------------------------------------
        */

        $personnel =
            $this->attendancePersonnelQuery(
                $user->department_id
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Automated Availability Checks
        |--------------------------------------------------------------------------
        |
        | Personnel are assignable only when:
        |
        | 1. There is no overlapping event assignment.
        | 2. There is no declared unavailable period that overlaps
        |    the selected event.
        |
        */

        $personnel->each(
            function ($person) use (
                $event,
                $assignedIds
            ) {

                /*
                |--------------------------------------------------------------------------
                | Existing Event Conflict
                |--------------------------------------------------------------------------
                */

                $scheduleConflict =
                    $this->findScheduleConflict(
                        $event,
                        $person->personnel_id
                    );


                /*
                |--------------------------------------------------------------------------
                | Declared Unavailable Period
                |--------------------------------------------------------------------------
                */

                $unavailablePeriod =
                    $this->findUnavailablePeriod(
                        $event,
                        $person->personnel_id
                    );


                /*
                |--------------------------------------------------------------------------
                | Current Assignment Status
                |--------------------------------------------------------------------------
                */

                $person->is_assigned =
                    in_array(
                        (int)
                            $person->personnel_id,
                        $assignedIds,
                        true
                    );


                /*
                |--------------------------------------------------------------------------
                | Event Schedule Conflict
                |--------------------------------------------------------------------------
                */

                $person->has_schedule_conflict =
                    $scheduleConflict
                    !== null;


                $person->conflicting_event_name =
                    $scheduleConflict
                        ? $scheduleConflict
                            ->event_name
                        : null;


                $person->conflicting_event_start =
                    $scheduleConflict
                        ? Carbon::parse(
                            $scheduleConflict
                                ->start_time
                        )->format(
                            'g:i A'
                        )
                        : null;


                $person->conflicting_event_end =
                    $scheduleConflict
                        ? Carbon::parse(
                            $scheduleConflict
                                ->end_time
                        )->format(
                            'g:i A'
                        )
                        : null;


                /*
                |--------------------------------------------------------------------------
                | Declared Unavailability
                |--------------------------------------------------------------------------
                */

                $person->has_unavailability =
                    $unavailablePeriod
                    !== null;


                $person->unavailable_start =
                    $unavailablePeriod
                        ? Carbon::parse(
                            $unavailablePeriod
                                ->start_time
                        )->format(
                            'g:i A'
                        )
                        : null;


                $person->unavailable_end =
                    $unavailablePeriod
                        ? Carbon::parse(
                            $unavailablePeriod
                                ->end_time
                        )->format(
                            'g:i A'
                        )
                        : null;


                $person->unavailability_reason =
                    $unavailablePeriod
                        ? $unavailablePeriod
                            ->reason
                        : null;


                /*
                |--------------------------------------------------------------------------
                | Final Assignment Status
                |--------------------------------------------------------------------------
                */

                $person->is_assignable =
                    !$person
                        ->has_schedule_conflict
                    &&
                    !$person
                        ->has_unavailability;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Hide Personnel Who Cannot Be Newly Assigned
        |--------------------------------------------------------------------------
        |
        | Available personnel:
        |     SHOW
        |
        | Unavailable personnel:
        |     HIDE
        |
        | Personnel with another event conflict:
        |     HIDE
        |
        | Already assigned personnel who later became unavailable:
        |     SHOW
        |
        | Existing unavailable/conflicted assignments remain visible so
        | Department Staff can remove them from the event.
        |
        */

        $personnel =
            $personnel
                ->filter(
                    function ($person) {

                        return
                            $person->is_assignable
                            ||
                            $person->is_assigned;

                    }
                )
                ->values();


        return view(
            'events.personnel-assignment',
            compact(
                'event',
                'personnel'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Save Assignments
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $event
    ) {

        $user =
            $this->departmentStaffUser();


        $event =
            $this->accessibleEvent(
                $event
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Input
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([

                'personnel_ids' => [
                    'nullable',
                    'array',
                ],

                'personnel_ids.*' => [
                    'integer',
                    'distinct',
                    'exists:personnel,personnel_id',
                ],

            ]);


        $selectedIds =
            collect(
                $validated[
                    'personnel_ids'
                ]
                ?? []
            )
            ->map(
                fn ($id) =>
                    (int) $id
            )
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Verify Personnel Accounts Are Eligible
        |--------------------------------------------------------------------------
        */

        $eligiblePersonnel =
            $this->attendancePersonnelQuery(
                $user->department_id
            )
            ->whereIn(
                'personnel_id',
                $selectedIds
            )
            ->get()
            ->keyBy(
                'personnel_id'
            );


        if (
            $eligiblePersonnel->count()
            !==
            $selectedIds->count()
        ) {

            throw ValidationException::withMessages([

                'personnel_ids' =>
                    'One or more selected personnel accounts are not eligible for this event.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Server-Side Availability Validation
        |--------------------------------------------------------------------------
        |
        | The system checks everything again here even though unavailable
        | personnel are hidden from the interface.
        |
        | This prevents manual requests from bypassing the rules.
        |
        */

        foreach (
            $selectedIds
            as $personnelId
        ) {

            $person =
                $eligiblePersonnel->get(
                    $personnelId
                );


            /*
            |--------------------------------------------------------------------------
            | Existing Event Conflict
            |--------------------------------------------------------------------------
            */

            $scheduleConflict =
                $this->findScheduleConflict(
                    $event,
                    $personnelId
                );


            if (
                $scheduleConflict
            ) {

                throw ValidationException::withMessages([

                    'personnel_ids' =>
                        $person->first_name
                        . ' '
                        . $person->last_name
                        . ' cannot be assigned because of a schedule conflict with "'
                        . $scheduleConflict
                            ->event_name
                        . '".',

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Declared Unavailability
            |--------------------------------------------------------------------------
            */

            $unavailablePeriod =
                $this->findUnavailablePeriod(
                    $event,
                    $personnelId
                );


            if (
                $unavailablePeriod
            ) {

                $unavailableTime =
                    Carbon::parse(
                        $unavailablePeriod
                            ->start_time
                    )->format(
                        'g:i A'
                    )
                    . ' - '
                    . Carbon::parse(
                        $unavailablePeriod
                            ->end_time
                    )->format(
                        'g:i A'
                    );


                $message =
                    $person->first_name
                    . ' '
                    . $person->last_name
                    . ' is unavailable from '
                    . $unavailableTime
                    . ' for this event.';


                if (
                    !empty(
                        $unavailablePeriod
                            ->reason
                    )
                ) {

                    $message .=
                        ' Reason: '
                        . $unavailablePeriod
                            ->reason;

                }


                throw ValidationException::withMessages([

                    'personnel_ids' =>
                        $message,

                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Save Assignments
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $event,
                $selectedIds
            ) {

                /*
                |--------------------------------------------------------------------------
                | Remove Unselected Personnel
                |--------------------------------------------------------------------------
                */

                if (
                    $selectedIds->isEmpty()
                ) {

                    DB::table(
                        'event_assignments'
                    )
                    ->where(
                        'event_id',
                        $event->event_id
                    )
                    ->delete();

                } else {

                    DB::table(
                        'event_assignments'
                    )
                    ->where(
                        'event_id',
                        $event->event_id
                    )
                    ->whereNotIn(
                        'personnel_id',
                        $selectedIds->all()
                    )
                    ->delete();

                }


                /*
                |--------------------------------------------------------------------------
                | Existing Assignments
                |--------------------------------------------------------------------------
                */

                $existingIds =
                    DB::table(
                        'event_assignments'
                    )
                    ->where(
                        'event_id',
                        $event->event_id
                    )
                    ->pluck(
                        'personnel_id'
                    )
                    ->map(
                        fn ($id) =>
                            (int) $id
                    )
                    ->all();


                /*
                |--------------------------------------------------------------------------
                | Add New Assignments
                |--------------------------------------------------------------------------
                */

                foreach (
                    $selectedIds
                    as $personnelId
                ) {

                    if (
                        in_array(
                            $personnelId,
                            $existingIds,
                            true
                        )
                    ) {

                        continue;

                    }


                    DB::table(
                        'event_assignments'
                    )
                    ->insert([

                        'event_id' =>
                            $event->event_id,

                        'personnel_id' =>
                            $personnelId,

                        'assigned_by' =>
                            auth()->id(),

                        'created_at' =>
                            now(),

                        'updated_at' =>
                            now(),

                    ]);
                }
            }
        );


        return redirect()
            ->route(
                'events.personnel-assignment.index',
                $event->event_id
            )
            ->with(
                'success',
                'Attendance personnel assignments updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Find Existing Event Schedule Conflict
    |--------------------------------------------------------------------------
    */

    private function findScheduleConflict(
        Event $event,
        int $personnelId
    ): ?object {

        [
            $eventDate,
            $eventStart,
            $eventEnd,
        ] =
            $this->eventSchedule(
                $event
            );


        return DB::table(
            'event_assignments as assignment'
        )
        ->join(
            'events as conflicting_event',
            'conflicting_event.event_id',
            '=',
            'assignment.event_id'
        )
        ->where(
            'assignment.personnel_id',
            $personnelId
        )
        ->where(
            'conflicting_event.event_id',
            '!=',
            $event->event_id
        )
        ->whereDate(
            'conflicting_event.event_date',
            $eventDate
        )
        ->where(
            'conflicting_event.status',
            '!=',
            'Cancelled'
        )
        ->where(
            'conflicting_event.start_time',
            '<',
            $eventEnd
        )
        ->where(
            'conflicting_event.end_time',
            '>',
            $eventStart
        )
        ->select([
            'conflicting_event.event_id',
            'conflicting_event.event_name',
            'conflicting_event.start_time',
            'conflicting_event.end_time',
        ])
        ->orderBy(
            'conflicting_event.start_time'
        )
        ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Find Declared Unavailable Period
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | Event:
    | 07:00 AM - 08:00 AM
    |
    | Personnel Unavailable:
    | 07:30 AM - 09:00 AM
    |
    | Result:
    | OVERLAP → NOT ASSIGNABLE
    |
    */

    private function findUnavailablePeriod(
        Event $event,
        int $personnelId
    ): ?PersonnelAvailability {

        [
            $eventDate,
            $eventStart,
            $eventEnd,
        ] =
            $this->eventSchedule(
                $event
            );


        return PersonnelAvailability::where(
            'personnel_id',
            $personnelId
        )
        ->whereDate(
            'availability_date',
            $eventDate
        )
        ->where(
            'start_time',
            '<',
            $eventEnd
        )
        ->where(
            'end_time',
            '>',
            $eventStart
        )
        ->orderBy(
            'start_time'
        )
        ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Event Schedule
    |--------------------------------------------------------------------------
    */

    private function eventSchedule(
        Event $event
    ): array {

        $eventDate =
            Carbon::parse(
                $event->event_date
            )
            ->format(
                'Y-m-d'
            );


        $eventStart =
            Carbon::parse(
                $event->start_time
            )
            ->format(
                'H:i:s'
            );


        $eventEnd =
            Carbon::parse(
                $event->end_time
            )
            ->format(
                'H:i:s'
            );


        return [
            $eventDate,
            $eventStart,
            $eventEnd,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel Query
    |--------------------------------------------------------------------------
    */

    private function attendancePersonnelQuery(
        ?int $departmentId
    ): Builder {

        return Personnel::query()
            ->with([
                'user.role',
                'user.department',
            ])
            ->whereHas(
                'user',
                function ($query) use (
                    $departmentId
                ) {

                    $query
                        ->where(
                            'status',
                            'Active'
                        )
                        ->where(
                            'must_change_password',
                            false
                        )
                        ->where(
                            function (
                                $departmentQuery
                            ) use (
                                $departmentId
                            ) {

                                $departmentQuery
                                    ->where(
                                        'department_id',
                                        $departmentId
                                    )
                                    ->orWhereNull(
                                        'department_id'
                                    );

                            }
                        );

                }
            )
            ->whereHas(
                'user.role',
                function ($query) {

                    $query->where(
                        'role_name',
                        'Attendance Personnel'
                    );

                }
            )
            ->orderBy(
                'last_name'
            )
            ->orderBy(
                'first_name'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Accessible Event
    |--------------------------------------------------------------------------
    */

    private function accessibleEvent(
        string $eventId
    ): Event {

        $user =
            $this->departmentStaffUser();


        Event::syncScheduledStatuses();


        $event =
            Event::with(
                'department'
            )
            ->where(
                'event_id',
                $eventId
            )
            ->where(
                'department_id',
                $user->department_id
            )
            ->firstOrFail();


        $event->syncScheduledStatus();


        $event->refresh();


        $event->load(
            'department'
        );


        if (
            $event->status
            !== 'Upcoming'
        ) {

            abort(
                403,
                'Personnel assignments can only be modified for upcoming events.'
            );
        }


        return $event;
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