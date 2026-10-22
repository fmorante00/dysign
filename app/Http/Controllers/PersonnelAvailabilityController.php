<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\PersonnelAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PersonnelAvailabilityController extends Controller
{
    private const TIMEZONE = 'Asia/Manila';


    /*
    |--------------------------------------------------------------------------
    | My Unavailability Schedule
    |--------------------------------------------------------------------------
    |
    | Attendance Personnel are considered AVAILABLE by default.
    |
    | They only need to record the date and time periods when they are
    | unavailable for attendance duties.
    |
    */

    public function index()
    {
        $personnel =
            $this->attendancePersonnel();


        $today =
            Carbon::now(
                self::TIMEZONE
            )->toDateString();


        /*
        |--------------------------------------------------------------------------
        | Current / Upcoming Unavailability
        |--------------------------------------------------------------------------
        */

        $upcomingAvailabilities =
            PersonnelAvailability::where(
                'personnel_id',
                $personnel->personnel_id
            )
            ->whereDate(
                'availability_date',
                '>=',
                $today
            )
            ->orderBy(
                'availability_date'
            )
            ->orderBy(
                'start_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Previous Records
        |--------------------------------------------------------------------------
        */

        $pastAvailabilities =
            PersonnelAvailability::where(
                'personnel_id',
                $personnel->personnel_id
            )
            ->whereDate(
                'availability_date',
                '<',
                $today
            )
            ->orderByDesc(
                'availability_date'
            )
            ->orderByDesc(
                'start_time'
            )
            ->take(20)
            ->get();


        return view(
            'personnel-availability.index',
            compact(
                'personnel',
                'upcomingAvailabilities',
                'pastAvailabilities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Unavailable Schedule
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ) {

        $personnel =
            $this->attendancePersonnel();


        $validated =
            $request->validate([

                'availability_date' => [
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

                'reason' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Date and Time
        |--------------------------------------------------------------------------
        */

        $this->validateScheduleTime(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Overlapping Unavailability
        |--------------------------------------------------------------------------
        */

        $this->ensureNoOverlap(
            $personnel,
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Store Record
        |--------------------------------------------------------------------------
        */

        PersonnelAvailability::create([

            'personnel_id' =>
                $personnel->personnel_id,

            'availability_date' =>
                $validated[
                    'availability_date'
                ],

            'start_time' =>
                $validated[
                    'start_time'
                ],

            'end_time' =>
                $validated[
                    'end_time'
                ],

            'reason' =>
                $validated[
                    'reason'
                ] ?? null,

        ]);


        return redirect()
            ->route(
                'personnel-availability.index'
            )
            ->with(
                'success',
                'Unavailable schedule added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Unavailable Schedule
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        PersonnelAvailability $availability
    ) {

        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Ownership Validation
        |--------------------------------------------------------------------------
        */

        $this->ensureOwned(
            $availability,
            $personnel
        );


        /*
        |--------------------------------------------------------------------------
        | Past Records Cannot Be Modified
        |--------------------------------------------------------------------------
        */

        $this->ensureEditable(
            $availability
        );


        /*
        |--------------------------------------------------------------------------
        | Input Validation
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([

                'availability_date' => [
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

                'reason' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | Date / Time Validation
        |--------------------------------------------------------------------------
        */

        $this->validateScheduleTime(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Overlap Validation
        |--------------------------------------------------------------------------
        */

        $this->ensureNoOverlap(
            $personnel,
            $validated,
            $availability->availability_id
        );


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $availability->update([

            'availability_date' =>
                $validated[
                    'availability_date'
                ],

            'start_time' =>
                $validated[
                    'start_time'
                ],

            'end_time' =>
                $validated[
                    'end_time'
                ],

            'reason' =>
                $validated[
                    'reason'
                ] ?? null,

        ]);


        return redirect()
            ->route(
                'personnel-availability.index'
            )
            ->with(
                'success',
                'Unavailable schedule updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Unavailable Schedule
    |--------------------------------------------------------------------------
    */

    public function destroy(
        PersonnelAvailability $availability
    ) {

        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Ownership Validation
        |--------------------------------------------------------------------------
        */

        $this->ensureOwned(
            $availability,
            $personnel
        );


        /*
        |--------------------------------------------------------------------------
        | Past Records Cannot Be Modified
        |--------------------------------------------------------------------------
        */

        $this->ensureEditable(
            $availability
        );


        /*
        |--------------------------------------------------------------------------
        | Delete Record
        |--------------------------------------------------------------------------
        */

        $availability->delete();


        return redirect()
            ->route(
                'personnel-availability.index'
            )
            ->with(
                'success',
                'Unavailable schedule removed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Department Staff View
    |--------------------------------------------------------------------------
    |
    | Department Staff can only VIEW the unavailable schedules.
    |
    */

    public function staffIndex(
        Request $request
    ) {

        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

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


        if (!$user->department_id) {

            abort(
                403,
                'Your account is not linked to a department.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'date' => [
                'nullable',
                'date',
            ],

        ]);


        $selectedDate =
            $request->input(
                'date',
                Carbon::now(
                    self::TIMEZONE
                )->toDateString()
            );


        /*
        |--------------------------------------------------------------------------
        | Attendance Personnel
        |--------------------------------------------------------------------------
        |
        | Show:
        | - personnel in the same department
        | - university-wide personnel without a department
        |
        */

        $personnel =
            Personnel::with([

                'user.role',

                'user.department',

                'availabilities' =>
                    function ($query) use (
                        $selectedDate
                    ) {

                        $query
                            ->whereDate(
                                'availability_date',
                                $selectedDate
                            )
                            ->orderBy(
                                'start_time'
                            );

                    },

            ])
            ->whereHas(
                'user',
                function ($query) use (
                    $user
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
                            ) use ($user) {

                                $departmentQuery
                                    ->where(
                                        'department_id',
                                        $user->department_id
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
            )
            ->get();


        return view(
            'personnel-availability.staff',
            compact(
                'personnel',
                'selectedDate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Logged-In Attendance Personnel
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
    | Ownership Validation
    |--------------------------------------------------------------------------
    */

    private function ensureOwned(
        PersonnelAvailability $availability,
        Personnel $personnel
    ): void {

        if (
            (int)
                $availability->personnel_id
            !==
            (int)
                $personnel->personnel_id
        ) {

            abort(403);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Past Schedule Protection
    |--------------------------------------------------------------------------
    */

    private function ensureEditable(
        PersonnelAvailability $availability
    ): void {

        $availabilityDate =
            Carbon::parse(
                $availability
                    ->availability_date
            )
            ->timezone(
                self::TIMEZONE
            )
            ->startOfDay();


        $today =
            Carbon::now(
                self::TIMEZONE
            )
            ->startOfDay();


        if (
            $availabilityDate->lt(
                $today
            )
        ) {

            throw ValidationException::withMessages([

                'availability' =>
                    'Past unavailable schedules can no longer be modified.',

            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Date and Time Validation
    |--------------------------------------------------------------------------
    */

    private function validateScheduleTime(
        array $data
    ): void {

        $date =
            Carbon::parse(
                $data[
                    'availability_date'
                ]
            )->format(
                'Y-m-d'
            );


        $startsAt =
            Carbon::createFromFormat(
                'Y-m-d H:i',
                $date
                . ' '
                . $data[
                    'start_time'
                ],
                self::TIMEZONE
            );


        $endsAt =
            Carbon::createFromFormat(
                'Y-m-d H:i',
                $date
                . ' '
                . $data[
                    'end_time'
                ],
                self::TIMEZONE
            );


        $now =
            Carbon::now(
                self::TIMEZONE
            );


        /*
        |--------------------------------------------------------------------------
        | End Must Be After Start
        |--------------------------------------------------------------------------
        */

        if (
            !$endsAt->gt(
                $startsAt
            )
        ) {

            throw ValidationException::withMessages([

                'end_time' =>
                    'End time must be later than the start time.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Past Schedule
        |--------------------------------------------------------------------------
        */

        if (
            $startsAt->lt(
                $now
            )
        ) {

            throw ValidationException::withMessages([

                'start_time' =>
                    'Unavailable time must be scheduled for the current or a future time.',

            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Prevent Overlapping Unavailable Periods
    |--------------------------------------------------------------------------
    |
    | Existing:
    | 08:00 - 12:00
    |
    | New:
    | 10:00 - 13:00
    |
    | Result:
    | REJECTED
    |
    | Boundary:
    | 08:00 - 12:00
    | 12:00 - 15:00
    |
    | Result:
    | ALLOWED
    |
    */

    private function ensureNoOverlap(
        Personnel $personnel,
        array $data,
        ?int $ignoreAvailabilityId = null
    ): void {

        $query =
            PersonnelAvailability::where(
                'personnel_id',
                $personnel->personnel_id
            )
            ->whereDate(
                'availability_date',
                $data[
                    'availability_date'
                ]
            )
            ->where(
                'start_time',
                '<',
                $data[
                    'end_time'
                ]
            )
            ->where(
                'end_time',
                '>',
                $data[
                    'start_time'
                ]
            );


        if (
            $ignoreAvailabilityId
        ) {

            $query->where(
                'availability_id',
                '!=',
                $ignoreAvailabilityId
            );
        }


        if (
            $query->exists()
        ) {

            throw ValidationException::withMessages([

                'start_time' =>
                    'This unavailable period overlaps with an existing schedule.',

            ]);
        }
    }
}