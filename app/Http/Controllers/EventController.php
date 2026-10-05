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
        $user = auth()->user();

        $this->authorizeEventAccess();


        $query = Event::with([
            'department',
            'creator',
        ])
        ->latest();


        /*
        |--------------------------------------------------------------------------
        | Department Access
        |--------------------------------------------------------------------------
        */

        if ($user->role->role_name === 'Department Staff') {

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

            $search = $request->search;

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


        $departments = Department::orderBy(
            'department_name'
        )
        ->get();


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

            if (!$user->department_id) {

                abort(
                    403,
                    'Your account is not assigned to a department.'
                );

            }


            $departments = Department::where(
                'department_id',
                $user->department_id
            )
            ->get();

        } else {

            $departments = Department::orderBy(
                'department_name'
            )
            ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | Available Attendance Personnel
        |--------------------------------------------------------------------------
        |
        | Only active users whose role is Attendance Personnel are displayed.
        |
        */

        $personnel = Personnel::whereHas(
            'user',
            function ($query) {

                $query->where(
                    'status',
                    'Active'
                )
                ->whereHas(
                    'role',
                    function ($roleQuery) {

                        $roleQuery->where(
                            'role_name',
                            'Attendance Personnel'
                        );

                    }
                );

            }
        )
        ->orderBy('last_name')
        ->orderBy('first_name')
        ->get();


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


        /*
        |--------------------------------------------------------------------------
        | Validate Event Data
        |--------------------------------------------------------------------------
        */

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
        |
        | Department Staff cannot submit another department manually.
        |
        */

        if ($user->role->role_name === 'Department Staff') {

            if (!$user->department_id) {

                abort(
                    403,
                    'Your account is not assigned to a department.'
                );

            }


            $validated['department_id'] =
                $user->department_id;

        }


        /*
        |--------------------------------------------------------------------------
        | Validate Selected Attendance Personnel
        |--------------------------------------------------------------------------
        |
        | The personnel ID must belong to:
        |
        | - an existing personnel profile
        | - an Active user
        | - the Attendance Personnel role
        |
        | This protects the request even if someone manually tampers with
        | the personnel_id submitted by the form.
        |
        */

        $selectedPersonnel = Personnel::where(
            'personnel_id',
            $validated['personnel_id']
        )
        ->whereHas(
            'user',
            function ($query) {

                $query->where(
                    'status',
                    'Active'
                )
                ->whereHas(
                    'role',
                    function ($roleQuery) {

                        $roleQuery->where(
                            'role_name',
                            'Attendance Personnel'
                        );

                    }
                );

            }
        )
        ->first();


        if (!$selectedPersonnel) {

            throw ValidationException::withMessages([

                'personnel_id' =>
                    'Please select an active Attendance Personnel account.',

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Separate Event Data From Assignment Data
        |--------------------------------------------------------------------------
        |
        | personnel_id belongs to event_assignments, not events.
        |
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
        | Create Event + Assignment Atomically
        |--------------------------------------------------------------------------
        |
        | If either operation fails, both are rolled back.
        | We will never leave an event without its personnel assignment.
        |
        */

        DB::transaction(function () use (
            $validated,
            $personnelId,
            $user
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
            ->route('events.index')
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

            $departments = Department::where(
                'department_id',
                $user->department_id
            )
            ->get();

        } else {

            $departments = Department::orderBy(
                'department_name'
            )
            ->get();

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

            if (!$user->department_id) {

                abort(
                    403,
                    'Your account is not assigned to a department.'
                );

            }


            $validated['department_id'] =
                $user->department_id;

        }


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
    | Cancel Event
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $event = $this->accessibleEvent(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | Already Cancelled
        |--------------------------------------------------------------------------
        */

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
    |
    | Administrator:
    | - May access all events.
    |
    | Department Staff:
    | - May access events belonging only to their own department.
    |
    */

    private function accessibleEvent(
        string $id
    ): Event {

        $this->authorizeEventAccess();


        $user = auth()->user();


        $query = Event::with([
            'department',
            'creator',
            'assignments',
        ])
        ->where(
            'event_id',
            $id
        );


        if ($user->role->role_name === 'Department Staff') {

            $query->where(
                'department_id',
                $user->department_id
            );

        }


        return $query->firstOrFail();
    }
}