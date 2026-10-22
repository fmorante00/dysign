<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Event;
use App\Models\EventAssignment;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Event Directory
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $this->authorizeEventViewing();

        /*
         * Synchronize schedule-based statuses before
         * filtering or displaying the event directory.
         */
        Event::syncScheduledStatuses();

        $user = auth()->user();

        $query = Event::with([
            'department',
            'creator',
            'assignments.personnel.user.role',
            'assignments.personnel.user.department',
        ])
            ->withCount('assignments')
            ->orderByDesc('event_date')
            ->orderByDesc('start_time');


        /*
        |--------------------------------------------------------------------------
        | Department Access
        |--------------------------------------------------------------------------
        */

        if (
            $user->role->role_name ===
            'Department Staff'
        ) {

            $this->ensureDepartmentStaffHasDepartment();

            $query->where(
                'department_id',
                $user->department_id
            );

        } elseif (
            $request->filled('department_id')
        ) {

            $query->where(
                'department_id',
                $request->department_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search =
                trim($request->search);

            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'event_name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'location',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'description',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('event_date')) {

            $query->whereDate(
                'event_date',
                $request->event_date
            );
        }


        $events =
            $query->get();


        /*
        |--------------------------------------------------------------------------
        | Department Options
        |--------------------------------------------------------------------------
        */

        if (
            $user->role->role_name ===
            'Department Staff'
        ) {

            $departments = Department::where(
                'department_id',
                $user->department_id
            )->get();

        } else {

            $departments = Department::orderBy(
                'department_name'
            )->get();
        }


        return view(
            'events.index',
            compact(
                'events',
                'departments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Event
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
         * Only Department Staff may create events.
         */
        $this->authorizeEventManagement();

        $this->ensureDepartmentStaffHasDepartment();

        return view(
            'events.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Event
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
         * Only Department Staff may save events.
         */
        $this->authorizeEventManagement();

        $user = auth()->user();

        $this->ensureDepartmentStaffHasDepartment();


        $validated = $request->validate([

            'event_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'event_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Automatic Ownership
        |--------------------------------------------------------------------------
        |
        | Department information is never trusted from the browser.
        | The event automatically belongs to the logged-in
        | Department Staff account's department.
        |
        */

        $validated['department_id'] =
            $user->department_id;

        $validated['created_by'] =
            $user->user_id;

        /*
         * Initial value is immediately recalculated below.
         */
        $validated['status'] =
            'Upcoming';


        /*
        |--------------------------------------------------------------------------
        | Create Event
        |--------------------------------------------------------------------------
        |
        | Personnel assignment is NOT performed here.
        | It belongs to Event Personnel Assignment Management.
        |
        */

        $event = Event::create(
            $validated
        );


        /*
         * Determine the correct status from
         * the event schedule.
         */
        $event->syncScheduledStatus();


        return redirect()
            ->route(
                'events.show',
                $event->event_id
            )
            ->with(
                'success',
                'Event created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Event
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $event =
            $this->accessibleEvent($id);

        return view(
            'events.show',
            compact('event')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Event
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        /*
         * Only Department Staff may edit events.
         */
        $this->authorizeEventManagement();

        $event =
            $this->accessibleEvent($id);

        $this->ensureDepartmentStaffHasDepartment();


        if ($event->status === 'Cancelled') {

            return redirect()
                ->route(
                    'events.show',
                    $event->event_id
                )
                ->with(
                    'error',
                    'Cancelled events can no longer be edited.'
                );
        }


        return view(
            'events.edit',
            compact('event')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Event
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {
        /*
         * Only Department Staff may update events.
         */
        $this->authorizeEventManagement();

        $event =
            $this->accessibleEvent($id);

        $this->ensureDepartmentStaffHasDepartment();


        if ($event->status === 'Cancelled') {

            return redirect()
                ->route(
                    'events.show',
                    $event->event_id
                )
                ->with(
                    'error',
                    'Cancelled events can no longer be edited.'
                );
        }


        $validated = $request->validate([

            'event_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'event_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Basic Event Information Only
        |--------------------------------------------------------------------------
        |
        | Department cannot be changed here.
        | Personnel assignments are not changed here.
        | Status is not manually submitted.
        |
        */

        $event->update(
            $validated
        );


        /*
         * Recalculate event status because the
         * date/time may have changed.
         */
        $event->syncScheduledStatus();


        return redirect()
            ->route(
                'events.show',
                $event->event_id
            )
            ->with(
                'success',
                'Event updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Manage Event Personnel
    |--------------------------------------------------------------------------
    */

    public function personnel(string $id)
    {
        /*
         * Only Department Staff may access
         * personnel assignment management.
         */
        $this->authorizeEventManagement();

        $event =
            $this->accessibleEvent($id);


        $availablePersonnel = $this
            ->attendancePersonnelQuery(
                $event->department_id
            )
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();


        $assignedPersonnelIds = $event
            ->assignments
            ->pluck('personnel_id')
            ->map(
                fn ($id) => (int) $id
            )
            ->values();


        return view(
            'events.assign',
            compact(
                'event',
                'availablePersonnel',
                'assignedPersonnelIds'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Event Personnel
    |--------------------------------------------------------------------------
    */

    public function updatePersonnel(
        Request $request,
        string $id
    ) {
        /*
         * Only Department Staff may modify
         * personnel assignments.
         */
        $this->authorizeEventManagement();

        $event =
            $this->accessibleEvent($id);


        if (
            in_array(
                $event->status,
                [
                    'Completed',
                    'Cancelled',
                ],
                true
            )
        ) {

            throw ValidationException::withMessages([

                'personnel_ids' =>
                    'Personnel assignments can no longer be changed for a completed or cancelled event.',

            ]);
        }


        $validated = $request->validate([

            'personnel_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'personnel_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:personnel,personnel_id',
            ],

        ]);


        $submittedIds = collect(
            $validated['personnel_ids']
        )
            ->map(
                fn ($id) => (int) $id
            )
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Eligible Attendance Personnel
        |--------------------------------------------------------------------------
        */

        $eligibleIds = $this
            ->attendancePersonnelQuery(
                $event->department_id
            )
            ->whereIn(
                'personnel_id',
                $submittedIds->all()
            )
            ->pluck('personnel_id')
            ->map(
                fn ($id) => (int) $id
            );


        $invalidIds =
            $submittedIds->diff(
                $eligibleIds
            );


        if ($invalidIds->isNotEmpty()) {

            throw ValidationException::withMessages([

                'personnel_ids' =>
                    'One or more selected personnel accounts are inactive, not fully set up, not Attendance Personnel, or not allowed for this department.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Synchronize Assignments
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $event,
                $submittedIds
            ) {

                $currentIds = EventAssignment::where(
                    'event_id',
                    $event->event_id
                )
                    ->pluck('personnel_id')
                    ->map(
                        fn ($id) => (int) $id
                    );


                /*
                 * Remove personnel no longer selected.
                 */

                $removeIds =
                    $currentIds->diff(
                        $submittedIds
                    );


                if ($removeIds->isNotEmpty()) {

                    EventAssignment::where(
                        'event_id',
                        $event->event_id
                    )
                        ->whereIn(
                            'personnel_id',
                            $removeIds->all()
                        )
                        ->delete();
                }


                /*
                 * Add newly selected personnel.
                 */

                $addIds =
                    $submittedIds->diff(
                        $currentIds
                    );


                foreach ($addIds as $personnelId) {

                    EventAssignment::create([

                        'event_id' =>
                            $event->event_id,

                        'personnel_id' =>
                            $personnelId,

                        'assigned_by' =>
                            auth()->id(),

                    ]);
                }
            }
        );


        return redirect()
            ->route(
                'events.show',
                $event->event_id
            )
            ->with(
                'success',
                'Event personnel assignments updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Cancel Event
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        /*
         * Only Department Staff may cancel events.
         */
        $this->authorizeEventManagement();

        $event =
            $this->accessibleEvent($id);


        if ($event->status === 'Cancelled') {

            return redirect()
                ->route(
                    'events.show',
                    $event->event_id
                )
                ->with(
                    'error',
                    'This event is already cancelled.'
                );
        }


        if ($event->status === 'Completed') {

            return redirect()
                ->route(
                    'events.show',
                    $event->event_id
                )
                ->with(
                    'error',
                    'A completed event can no longer be cancelled.'
                );
        }


        /*
         * Cancelled is the only manually controlled
         * lifecycle status.
         */
        $event->update([
            'status' => 'Cancelled',
        ]);


        return redirect()
            ->route(
                'events.show',
                $event->event_id
            )
            ->with(
                'success',
                'Event cancelled successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel Query
    |--------------------------------------------------------------------------
    */

    private function attendancePersonnelQuery(
        ?int $departmentId = null
    ) {
        return Personnel::query()
            ->with([
                'user.role',
                'user.department',
            ])
            ->whereHas(
                'user',
                function ($userQuery) use (
                    $departmentId
                ) {

                    $userQuery
                        ->where(
                            'status',
                            'Active'
                        )
                        ->where(
                            'must_change_password',
                            false
                        )
                        ->whereHas(
                            'role',
                            function ($roleQuery) {

                                $roleQuery
                                    ->where(
                                        'role_name',
                                        'Attendance Personnel'
                                    )
                                    ->where(
                                        'status',
                                        'Active'
                                    );
                            }
                        );


                    /*
                     * Department-specific event:
                     *
                     * Personnel may either belong to the same
                     * department or be university-wide.
                     */

                    if ($departmentId !== null) {

                        $userQuery->where(
                            function ($departmentQuery) use (
                                $departmentId
                            ) {

                                $departmentQuery
                                    ->whereNull(
                                        'department_id'
                                    )
                                    ->orWhere(
                                        'department_id',
                                        $departmentId
                                    );
                            }
                        );
                    }
                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Department Staff Department Check
    |--------------------------------------------------------------------------
    */

    private function ensureDepartmentStaffHasDepartment(): void
    {
        $user =
            auth()->user();


        if (
            $user->role->role_name ===
                'Department Staff'
            && !$user->department_id
        ) {

            abort(
                403,
                'Your account is not assigned to a department.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Event Viewing Authorization
    |--------------------------------------------------------------------------
    |
    | Administrator:
    | - View all event records.
    |
    | Department Staff:
    | - View events belonging to their department.
    |
    */

    private function authorizeEventViewing(): void
    {
        $user =
            auth()->user();

        $role =
            $user->role->role_name
            ?? null;


        if (
            !in_array(
                $role,
                [
                    'Administrator',
                    'Department Staff',
                ],
                true
            )
        ) {

            abort(
                403,
                'You are not authorized to view event records.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Event Management Authorization
    |--------------------------------------------------------------------------
    |
    | Only Department Staff may perform operational
    | Event Management.
    |
    | Administrator has read-only oversight access.
    |
    */

    private function authorizeEventManagement(): void
    {
        $user =
            auth()->user();

        $role =
            $user->role->role_name
            ?? null;


        if ($role !== 'Department Staff') {

            abort(
                403,
                'Only Department Staff can manage events.'
            );
        }


        $this->ensureDepartmentStaffHasDepartment();
    }


    /*
    |--------------------------------------------------------------------------
    | Accessible Event
    |--------------------------------------------------------------------------
    */

    private function accessibleEvent(
        string $id
    ): Event {

        $this->authorizeEventViewing();

        $user =
            auth()->user();


        if (
            $user->role->role_name ===
            'Department Staff'
        ) {

            $this->ensureDepartmentStaffHasDepartment();
        }


        $query = Event::with([

            'department',

            'creator',

            'assignments.personnel.user.role',

            'assignments.personnel.user.department',

            'assignments.assignedBy',

        ])
            ->where(
                'event_id',
                $id
            );


        if (
            $user->role->role_name ===
            'Department Staff'
        ) {

            $query->where(
                'department_id',
                $user->department_id
            );
        }


        $event =
            $query->firstOrFail();


        /*
         * Keep this specific event's status
         * synchronized whenever it is accessed.
         */
        $event->syncScheduledStatus();


        return $event;
    }
}