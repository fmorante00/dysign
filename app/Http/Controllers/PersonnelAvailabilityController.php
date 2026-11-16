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

    public function index(Request $request)
    {
        $personnel = $this->attendancePersonnel();
        $now = Carbon::now(self::TIMEZONE);
        $today = $now->toDateString();
        $timeNow = $now->format('H:i:s');

        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'period' => ['nullable', 'in:upcoming,past,all'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'sort' => ['nullable', 'in:soonest,latest'],
            'per_page' => ['nullable', 'in:5,10,20'],
        ]);

        $filters = [
            'q' => trim($validated['q'] ?? ''),
            'period' => $validated['period'] ?? 'upcoming',
            'from' => $validated['from'] ?? '',
            'to' => $validated['to'] ?? '',
            'sort' => $validated['sort'] ?? 'soonest',
            'per_page' => (int) ($validated['per_page'] ?? 5),
        ];

        $base = PersonnelAvailability::query()
            ->where('personnel_id', $personnel->personnel_id);

        $upcomingScope = function ($query) use ($today, $timeNow): void {
            $query->where(function ($q) use ($today, $timeNow): void {
                $q->whereDate('availability_date', '>', $today)
                    ->orWhere(function ($sameDay) use ($today, $timeNow): void {
                        $sameDay->whereDate('availability_date', '=', $today)
                            ->where('end_time', '>', $timeNow);
                    });
            });
        };

        $pastScope = function ($query) use ($today, $timeNow): void {
            $query->where(function ($q) use ($today, $timeNow): void {
                $q->whereDate('availability_date', '<', $today)
                    ->orWhere(function ($sameDay) use ($today, $timeNow): void {
                        $sameDay->whereDate('availability_date', '=', $today)
                            ->where('end_time', '<=', $timeNow);
                    });
            });
        };

        $upcomingCount = (clone $base)->where($upcomingScope)->count();
        $totalCount = (clone $base)->count();
        $nextUnavailable = (clone $base)
            ->where($upcomingScope)
            ->orderBy('availability_date')
            ->orderBy('start_time')
            ->first();

        $query = clone $base;
        if ($filters['period'] === 'upcoming') {
            $query->where($upcomingScope);
        } elseif ($filters['period'] === 'past') {
            $query->where($pastScope);
        }

        if ($filters['q'] !== '') {
            $term = $filters['q'];
            $query->where(function ($q) use ($term): void {
                $q->where('reason', 'like', '%' . $term . '%');
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $term)) {
                    $q->orWhereDate('availability_date', $term);
                }
            });
        }
        if ($filters['from'] !== '') {
            $query->whereDate('availability_date', '>=', $filters['from']);
        }
        if ($filters['to'] !== '') {
            $query->whereDate('availability_date', '<=', $filters['to']);
        }

        $direction = $filters['sort'] === 'latest' ? 'desc' : 'asc';
        $entries = $query
            ->orderBy('availability_date', $direction)
            ->orderBy('start_time', $direction)
            ->orderBy('availability_id', $direction)
            ->paginate($filters['per_page'])
            ->withQueryString();

        return view('personnel-availability.index', compact(
            'personnel', 'entries', 'filters', 'upcomingCount',
            'totalCount', 'nextUnavailable', 'today', 'timeNow'
        ));
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

    public function staffIndex(Request $request)
    {
        $user = $request->user();
        abort_unless($user && $user->role?->role_name === 'Department Staff', 403);
        abort_unless($user->department_id, 403, 'Your account is not linked to a department.');

        $input = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'scope' => ['nullable', 'in:upcoming,past,all'],
            'kind' => ['nullable', 'in:all,department,schoolwide'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'sort' => ['nullable', 'in:soonest,latest'],
            'per_page' => ['nullable', 'integer', 'in:5,10,20'],
            'check_date' => ['nullable', 'date_format:Y-m-d'],
            'page' => ['nullable', 'integer', 'min:1'],
            'people_page' => ['nullable', 'integer', 'min:1'],
        ]);

        $search = trim((string) ($input['search'] ?? ''));
        $scope = $input['scope'] ?? 'upcoming';
        $kind = $input['kind'] ?? 'all';
        $from = $input['from'] ?? '';
        $to = $input['to'] ?? '';
        $sort = $input['sort'] ?? 'soonest';
        $perPage = (int) ($input['per_page'] ?? 5);
        $checkDate = $input['check_date'] ?? '';
        $today = Carbon::now(self::TIMEZONE)->toDateString();

        if ($from !== '' && $to !== '' && $from > $to) {
            throw ValidationException::withMessages([
                'to' => 'The end date must be on or after the start date.',
            ]);
        }

        // Every query is limited to ACTIVE Attendance Personnel in the staff
        // member's department, plus personnel with no department assignment.
        // The query is enforced on the server, including direct URL requests.
        $peopleQuery = Personnel::query()
            ->whereHas('user', function ($query) use ($user) {
                $query->where('status', 'Active')
                    ->where('must_change_password', false)
                    ->where(function ($departments) use ($user) {
                        $departments->where('department_id', $user->department_id)
                            ->orWhereNull('department_id');
                    });
            })
            ->whereHas('user.role', function ($query) {
                $query->where('role_name', 'Attendance Personnel');
            });

        if ($kind === 'department') {
            $peopleQuery->whereHas('user', fn ($query) =>
                $query->where('department_id', $user->department_id));
        } elseif ($kind === 'schoolwide') {
            $peopleQuery->whereHas('user', fn ($query) =>
                $query->whereNull('department_id'));
        }

        // Tokenized search also supports full names such as "Adrian Gomez".
        if ($search !== '') {
            foreach (preg_split('/\\s+/', $search) as $token) {
                $like = '%' . $token . '%';
                $peopleQuery->where(function ($query) use ($like) {
                    $query->where('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('position', 'like', $like);
                });
            }
        }

        $personnelCount = (clone $peopleQuery)->count();
        $periodsQuery = PersonnelAvailability::query()
            ->whereIn('personnel_id', (clone $peopleQuery)->select('personnel_id'));

        if ($scope === 'upcoming') {
            $periodsQuery->whereDate('availability_date', '>=', $today);
        } elseif ($scope === 'past') {
            $periodsQuery->whereDate('availability_date', '<', $today);
        }
        if ($from !== '') {
            $periodsQuery->whereDate('availability_date', '>=', $from);
        }
        if ($to !== '') {
            $periodsQuery->whereDate('availability_date', '<=', $to);
        }

        $periodCount = (clone $periodsQuery)->count();
        $peopleWithPeriods = (clone $periodsQuery)
            ->distinct()->count('personnel_id');

        $periods = (clone $periodsQuery)
            ->with(['personnel.user.department']);
        if ($sort === 'latest') {
            $periods->orderByDesc('availability_date')
                ->orderByDesc('start_time')->orderByDesc('availability_id');
        } else {
            $periods->orderBy('availability_date')
                ->orderBy('start_time')->orderBy('availability_id');
        }
        $periods = $periods->paginate($perPage, ['*'], 'page')
            ->withQueryString();

        // Optional day-by-day roster; no day is assumed until staff selects one.
        // A missing declaration means "no declared unavailability", not proof
        // that an event's entire time window is conflict-free.
        $dailyPeople = null;
        $dailyConflictCount = 0;
        if ($checkDate !== '') {
            $dailyPeople = (clone $peopleQuery)
                ->with([
                    'user.department',
                    'availabilities' => function ($query) use ($checkDate) {
                        $query->whereDate('availability_date', $checkDate)
                            ->orderBy('start_time');
                    },
                ])
                ->orderBy('last_name')->orderBy('first_name')
                ->paginate($perPage, ['*'], 'people_page')
                ->withQueryString();
            $dailyConflictCount = PersonnelAvailability::query()
                ->whereIn('personnel_id', (clone $peopleQuery)->select('personnel_id'))
                ->whereDate('availability_date', $checkDate)
                ->distinct()->count('personnel_id');
        }

        return view('personnel-availability.staff', compact(
            'search', 'scope', 'kind', 'from', 'to', 'sort', 'perPage',
            'checkDate', 'today', 'personnelCount', 'periodCount',
            'peopleWithPeriods', 'periods', 'dailyPeople', 'dailyConflictCount'
        ));
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