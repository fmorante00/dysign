<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Event;
use App\Models\AttendanceRecord;
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
        $this->authorizeEventAccess();

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

        if ($user->role->role_name === 'Department Staff') {

            $this->ensureDepartmentStaffHasDepartment();

            $query->where(
                'department_id',
                $user->department_id
            );

        } elseif ($request->filled('department_id')) {

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

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

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
            });
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


        $events = $query->get();


        /*
        |--------------------------------------------------------------------------
        | Department Options
        |--------------------------------------------------------------------------
        */

        if ($user->role->role_name === 'Department Staff') {

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
        $this->authorizeEventAccess();

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Department Options
        |--------------------------------------------------------------------------
        */

        if ($user->role->role_name === 'Department Staff') {

            $this->ensureDepartmentStaffHasDepartment();

            $departments = Department::where(
                'department_id',
                $user->department_id
            )->get();

            /*
             * Department Staff can only select personnel that are either:
             *
             * 1. assigned to the same department; or
             * 2. university-wide personnel with department_id = NULL.
             */

            $personnel = $this
                ->attendancePersonnelQuery(
                    $user->department_id
                )
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();

        } else {

            $departments = Department::orderBy(
                'department_name'
            )->get();

            /*
             * Administrator initially receives every eligible
             * Attendance Personnel account.
             *
             * The Create Event view can filter this list dynamically
             * when a department is selected.
             */

            $personnel = $this
                ->attendancePersonnelQuery()
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        }


        return view(
            'events.create',
            compact(
                'departments',
                'personnel'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Event
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $this->authorizeEventAccess();

        $user = auth()->user();


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

            'department_id' => [
                'nullable',
                'integer',
                'exists:departments,department_id',
            ],

            'personnel_id' => [
                'required',
                'integer',
                'exists:personnel,personnel_id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Department Staff Security
        |--------------------------------------------------------------------------
        */

        if ($user->role->role_name === 'Department Staff') {

            $this->ensureDepartmentStaffHasDepartment();

            /*
             * Never trust department_id coming from the browser
             * for a Department Staff account.
             */

            $validated['department_id'] =
                $user->department_id;
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Attendance Personnel
        |--------------------------------------------------------------------------
        */

        $departmentId =
            $validated['department_id'] ?? null;


        $selectedPersonnel = $this
            ->attendancePersonnelQuery(
                $departmentId
            )
            ->where(
                'personnel_id',
                $validated['personnel_id']
            )
            ->first();


        if (!$selectedPersonnel) {

            throw ValidationException::withMessages([

                'personnel_id' =>
                    'Please select an active Attendance Personnel account that is allowed for this department.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Separate Event Data From Assignment Data
        |--------------------------------------------------------------------------
        */

        $personnelId =
            $selectedPersonnel->personnel_id;


        unset(
            $validated['personnel_id']
        );


        $validated['created_by'] =
            $user->user_id;


        $validated['status'] =
            'Upcoming';


        /*
        |--------------------------------------------------------------------------
        | Create Event + Initial Personnel Assignment
        |--------------------------------------------------------------------------
        */

        $event = null;


        DB::transaction(function () use (
            $validated,
            $personnelId,
            $user,
            &$event
        ) {

            $event = Event::create(
                $validated
            );


            EventAssignment::create([

                'event_id' =>
                    $event->event_id,

                'personnel_id' =>
                    $personnelId,

                'assigned_by' =>
                    $user->user_id,

            ]);
        });


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
        $event = $this->accessibleEvent(
            $id
        );


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
        $event = $this->accessibleEvent(
            $id
        );


        $user = auth()->user();


        if ($user->role->role_name === 'Department Staff') {

            $this->ensureDepartmentStaffHasDepartment();

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
            'events.edit',
            compact(
                'event',
                'departments'
            )
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

        $event = $this->accessibleEvent(
            $id
        );


        $user = auth()->user();


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

            'department_id' => [
                'nullable',
                'integer',
                'exists:departments,department_id',
            ],

            'status' => [
                'required',
                'in:Upcoming,Ongoing,Completed,Cancelled',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Department Staff Cannot Change Department
        |--------------------------------------------------------------------------
        */

        if ($user->role->role_name === 'Department Staff') {

            $this->ensureDepartmentStaffHasDepartment();

            $validated['department_id'] =
                $user->department_id;
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Existing Personnel Still Match Department
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | If an Administrator changes an event from CCS to Engineering,
        | a personnel account specifically assigned to CCS should not remain
        | assigned accidentally.
        |
        */

        $this->validateExistingAssignmentsForDepartment(
            $event,
            $validated['department_id'] ?? null
        );


        $event->update(
            $validated
        );


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
        $event = $this->accessibleEvent(
            $id
        );


        /*
         * Completed and Cancelled events may still be viewed,
         * but their assignments should no longer be changed.
         */

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

        $event = $this->accessibleEvent(
            $id
        );


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


        $invalidIds = $submittedIds->diff(
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

        DB::transaction(function () use (
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

        });


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
        $event = $this->accessibleEvent(
            $id
        );


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


        $event->update([

            'status' =>
                'Cancelled',

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
    |
    | Requirements:
    |
    | - Personnel profile exists.
    | - User account is Active.
    | - Account setup is completed.
    | - Role is Attendance Personnel.
    | - Role itself is Active.
    |
    | Department rule:
    |
    | A personnel account with NULL department_id is treated as
    | university-wide personnel.
    |
    | For a department event, eligible personnel are:
    |
    | - personnel from the same department; OR
    | - university-wide personnel.
    |
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
    | Validate Assigned Personnel Against Department
    |--------------------------------------------------------------------------
    */

    private function validateExistingAssignmentsForDepartment(
        Event $event,
        ?int $departmentId
    ): void {

        /*
         * University-wide event:
         * all eligible Attendance Personnel departments are acceptable.
         */

        if ($departmentId === null) {

            return;
        }


        $hasInvalidAssignment = EventAssignment::where(
            'event_id',
            $event->event_id
        )
        ->whereHas(
            'personnel.user',
            function ($query) use (
                $departmentId
            ) {

                $query
                    ->whereNotNull(
                        'department_id'
                    )
                    ->where(
                        'department_id',
                        '!=',
                        $departmentId
                    );
            }
        )
        ->exists();


        if ($hasInvalidAssignment) {

            throw ValidationException::withMessages([

                'department_id' =>
                    'This event currently has personnel assigned from another department. Update the personnel assignments before changing the event department.',

            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Department Staff Department Check
    |--------------------------------------------------------------------------
    */

    private function ensureDepartmentStaffHasDepartment(): void
    {
        $user = auth()->user();


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
    | Event Management Authorization
    |--------------------------------------------------------------------------
    */

    private function authorizeEventAccess(): void
    {
        $user = auth()->user();


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

            abort(403);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Accessible Event
    |--------------------------------------------------------------------------
    */

    private function accessibleEvent(
        string $id
    ): Event {

        $this->authorizeEventAccess();


        $user = auth()->user();


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


        return $query->firstOrFail();
    }
}