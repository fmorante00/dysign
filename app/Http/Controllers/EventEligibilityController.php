<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Department;
use App\Models\EventEligibilityRule;
use App\Models\EventEligibleStudent;
use App\Models\Student;
use App\Support\DepartmentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventEligibilityMail;
use Illuminate\Validation\ValidationException;

class EventEligibilityController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Eligibility Module Event List
    |--------------------------------------------------------------------------
    */

    public function events(Request $request)
    {
        $this->authorizeEligibilityManagement();
        Event::syncScheduledStatuses();

        $user = auth()->user();
        $isAdministrator = $user->role?->role_name === 'Administrator';

        // Administrators see every event in read-only mode. Department staff
        // see the complete history for their own department, including past events.
        $baseQuery = Event::query()
            ->when(!$isAdministrator, fn ($query) => $query
                ->where('department_id', $user->department_id));

        $statusCounts = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $eventTotals = [
            'total' => (int) $statusCounts->sum(),
            'Upcoming' => (int) ($statusCounts['Upcoming'] ?? 0),
            'Ongoing' => (int) ($statusCounts['Ongoing'] ?? 0),
            'Completed' => (int) ($statusCounts['Completed'] ?? 0),
            'Cancelled' => (int) ($statusCounts['Cancelled'] ?? 0),
        ];

        $search = trim(substr((string) $request->query('search', ''), 0, 120));
        $status = (string) $request->query('status', 'all');
        if (!in_array($status, ['all', 'Upcoming', 'Ongoing', 'Completed', 'Cancelled'], true)) {
            $status = 'all';
        }
        $departmentId = (string) $request->query('department_id', '');

        $query = $baseQuery->with(['department'])
            ->withCount(['eligibilityRules', 'eligibleStudents']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%');
            });
        }
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($isAdministrator && ctype_digit($departmentId) && (int) $departmentId > 0) {
            $query->where('department_id', (int) $departmentId);
        }

        // Future sessions first, followed by recent historical records.
        $events = $query
            ->orderByRaw("CASE WHEN status IN ('Ongoing', 'Upcoming') THEN 0 ELSE 1 END")
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        $departments = $isAdministrator
            ? Department::orderBy('department_name')->get()
            : collect();

        return view('events.eligibility-events', compact(
            'events', 'eventTotals', 'departments', 'isAdministrator',
            'search', 'status', 'departmentId'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Eligibility Configuration
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        string $event
    ) {

        $event =
            $this->accessibleEvent(
                $event
            );

        $isAdministrator = auth()->user()?->role?->role_name === 'Administrator';

        $rules =
            EventEligibilityRule::with([
                'student',
            ])
                ->where(
                    'event_id',
                    $event->event_id
                )
                ->orderByDesc(
                    'eligibility_rule_id'
                )
                ->get();

        if ($isAdministrator || $event->status !== 'Upcoming') {
            // Administrators and department staff reviewing non-upcoming events
            // can only inspect saved rules and generated participants.
            // Modification endpoints retain their independent 403/validation guards.
            $participation = (string) $request->query('participation', 'all');
            if (!in_array($participation, ['all', 'Required', 'Optional'], true)) {
                $participation = 'all';
            }
            $search = trim(substr((string) $request->query('search', ''), 0, 120));

            $eligibleQuery = EventEligibleStudent::with('student')
                ->where('event_id', $event->event_id);

            if ($participation !== 'all') {
                $eligibleQuery->where('participation_type', $participation);
            }
            if ($search !== '') {
                $eligibleQuery->whereHas('student', function ($q) use ($search) {
                    $q->where(function ($studentQuery) use ($search) {
                        $studentQuery->where('student_number', 'like', '%'.$search.'%')
                            ->orWhere('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%');
                    });
                });
            }

            $eligibleStudents = $eligibleQuery
                ->orderBy('student_id')
                ->paginate(15)
                ->withQueryString();

            $participantCounts = EventEligibleStudent::query()
                ->where('event_id', $event->event_id)
                ->selectRaw('participation_type, COUNT(*) as total')
                ->groupBy('participation_type')
                ->pluck('total', 'participation_type');

            return view('events.eligibility-readonly', compact(
                'event', 'rules', 'eligibleStudents', 'participantCounts',
                'participation', 'search'
            ));
        }


        $eligibleStudents =
            EventEligibleStudent::with([
                'student',
            ])
                ->where(
                    'event_id',
                    $event->event_id
                )
                ->get()
                ->sortBy(
                    function ($record) {

                        return strtolower(
                            (
                                $record->student
                                    ?->last_name
                                ?? ''
                            )
                            .
                            (
                                $record->student
                                    ?->first_name
                                ?? ''
                            )
                        );
                    }
                )
                ->values();


        $colleges =
            DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department)
                ->whereNotNull(
                    'college'
                )
                ->where(
                    'college',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'college'
                )
                ->pluck(
                    'college'
                );


        $programs =
            DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department)
                ->whereNotNull(
                    'program_code'
                )
                ->where(
                    'program_code',
                    '!=',
                    ''
                )
                ->select([
                    'program_code',
                    'program_name',
                ])
                ->distinct()
                ->orderBy(
                    'program_code'
                )
                ->get();


        $yearLevels =
            DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department)
                ->whereNotNull(
                    'year_level'
                )
                ->distinct()
                ->orderBy(
                    'year_level'
                )
                ->pluck(
                    'year_level'
                );


        /*
         * Used by Specific Student selection.
         */
        $students =
            DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department)
                ->orderBy(
                    'last_name'
                )
                ->orderBy(
                    'first_name'
                )
                ->get([
                    'student_id',
                    'student_number',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'college',
                    'program_code',
                    'year_level',
                ]);


        return view(
            'events.eligibility',
            compact(
                'event',
                'rules',
                'eligibleStudents',
                'colleges',
                'programs',
                'yearLevels',
                'students'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Eligibility Rule
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        string $event
    ) {

        $event =
            $this->accessibleEvent(
                $event
            );


        $this->ensureEligibilityCanBeModified(
            $event
        );


        $validated =
            $request->validate([

                'selection_mode' => [
                    'required',
                    'in:criteria,specific',
                ],

                'college' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'program_code' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'year_level' => [
                    'nullable',
                    'integer',
                    'min:1',
                    'max:10',
                ],

                'student_id' => [
                    'nullable',
                    'integer',
                    'exists:students,student_id',
                ],

                'participation_type' => [
                    'required',
                    'in:Required,Optional',
                ],

            ]);


        if (
            $validated['selection_mode']
            === 'specific'
        ) {

            return $this->storeSpecificStudentRule(
                $validated,
                $event
            );
        }


        return $this->storeCriteriaRule(
            $validated,
            $event
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Criteria Rule
    |--------------------------------------------------------------------------
    */

    private function storeCriteriaRule(
        array $validated,
        Event $event
    ) {

        $college =
            $validated['college']
            ?? null;

        $programCode =
            $validated['program_code']
            ?? null;

        $yearLevel =
            $validated['year_level']
            ?? null;


        /*
         * Verify College.
         */
        if ($college) {

            $exists =
                DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department)
                    ->where(
                        'college',
                        $college
                    )
                    ->exists();

            if (!$exists) {

                throw ValidationException::withMessages([

                    'college' =>
                        'The selected college does not exist in the active student registry.',

                ]);
            }
        }


        /*
         * Verify Program.
         */
        if ($programCode) {

            $exists =
                DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department)
                    ->where(
                        'program_code',
                        $programCode
                    )
                    ->exists();

            if (!$exists) {

                throw ValidationException::withMessages([

                    'program_code' =>
                        'The selected program does not exist in the active student registry.',

                ]);
            }
        }


        /*
         * Verify Year Level.
         */
        if ($yearLevel) {

            $exists =
                DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department)
                    ->where(
                        'year_level',
                        $yearLevel
                    )
                    ->exists();

            if (!$exists) {

                throw ValidationException::withMessages([

                    'year_level' =>
                        'The selected year level does not exist in the active student registry.',

                ]);
            }
        }


        /*
         * Check whether the combination
         * actually matches a student.
         */
        $matchingQuery =
            DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department);


        if ($college) {

            $matchingQuery->where(
                'college',
                $college
            );
        }


        if ($programCode) {

            $matchingQuery->where(
                'program_code',
                $programCode
            );
        }


        if ($yearLevel) {

            $matchingQuery->where(
                'year_level',
                $yearLevel
            );
        }


        if (!$matchingQuery->exists()) {

            throw ValidationException::withMessages([

                'college' =>
                    'No active students match the selected eligibility criteria.',

            ]);
        }


        $newlyEligible = DB::transaction(
            function () use (
                $event,
                $college,
                $programCode,
                $yearLevel,
                $validated
            ) {

                /*
                 * Look for the same rule first.
                 */
                $query =
                    EventEligibilityRule::where(
                        'event_id',
                        $event->event_id
                    )
                        ->where(
                            'rule_type',
                            'Criteria'
                        );


                if ($college === null) {

                    $query->whereNull(
                        'college'
                    );

                } else {

                    $query->where(
                        'college',
                        $college
                    );
                }


                if ($programCode === null) {

                    $query->whereNull(
                        'program_code'
                    );

                } else {

                    $query->where(
                        'program_code',
                        $programCode
                    );
                }


                if ($yearLevel === null) {

                    $query->whereNull(
                        'year_level'
                    );

                } else {

                    $query->where(
                        'year_level',
                        $yearLevel
                    );
                }


                $existingRule =
                    $query->first();


                if ($existingRule) {

                    $existingRule->update([

                        'participation_type' =>
                            $validated[
                                'participation_type'
                            ],

                        'created_by' =>
                            auth()->id(),

                    ]);

                } else {

                    EventEligibilityRule::create([

                        'event_id' =>
                            $event->event_id,

                        'rule_type' =>
                            'Criteria',

                        'college' =>
                            $college,

                        'program_code' =>
                            $programCode,

                        'year_level' =>
                            $yearLevel,

                        'student_id' =>
                            null,

                        'participation_type' =>
                            $validated[
                                'participation_type'
                            ],

                        'created_by' =>
                            auth()->id(),

                    ]);
                }


                return $this->regenerateEligibleStudents(
                    $event
                );
            }
        );


        $emailNotice = $this->queueEligibilityEmails($event, $newlyEligible);

        return redirect()
            ->route(
                'events.eligibility.index',
                $event->event_id
            )
            ->with(
                'success',
                'Eligibility criteria saved and the eligible student list was regenerated.'
            )
            ->with('eligibility_email_notice', $emailNotice);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Specific Student Rule
    |--------------------------------------------------------------------------
    */

    private function storeSpecificStudentRule(
        array $validated,
        Event $event
    ) {

        if (
            empty(
                $validated['student_id']
            )
        ) {

            throw ValidationException::withMessages([

                'student_id' =>
                    'Please select a specific student.',

            ]);
        }


        $student =
            DepartmentAccess::scopeStudentsForDepartment(Student::where('student_id', $validated['student_id']), $event->department)
                ->where(
                    'status',
                    'Active'
                )
                ->first();


        if (!$student) {

            throw ValidationException::withMessages([

                'student_id' =>
                    'The selected student is not active or could not be found.',

            ]);
        }


        $newlyEligible = DB::transaction(
            function () use (
                $event,
                $student,
                $validated
            ) {

                $rule =
                    EventEligibilityRule::where(
                        'event_id',
                        $event->event_id
                    )
                        ->where(
                            'rule_type',
                            'Specific'
                        )
                        ->where(
                            'student_id',
                            $student->student_id
                        )
                        ->first();


                if ($rule) {

                    $rule->update([

                        'participation_type' =>
                            $validated[
                                'participation_type'
                            ],

                        'created_by' =>
                            auth()->id(),

                    ]);

                } else {

                    EventEligibilityRule::create([

                        'event_id' =>
                            $event->event_id,

                        'rule_type' =>
                            'Specific',

                        'college' =>
                            null,

                        'program_code' =>
                            null,

                        'year_level' =>
                            null,

                        'student_id' =>
                            $student->student_id,

                        'participation_type' =>
                            $validated[
                                'participation_type'
                            ],

                        'created_by' =>
                            auth()->id(),

                    ]);
                }


                return $this->regenerateEligibleStudents(
                    $event
                );
            }
        );


        $emailNotice = $this->queueEligibilityEmails($event, $newlyEligible);

        return redirect()
            ->route(
                'events.eligibility.index',
                $event->event_id
            )
            ->with(
                'success',
                'Specific student eligibility saved successfully.'
            )
            ->with('eligibility_email_notice', $emailNotice);
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Rule
    |--------------------------------------------------------------------------
    */

    public function destroy(
        string $event,
        string $rule
    ) {

        $event =
            $this->accessibleEvent(
                $event
            );


        $this->ensureEligibilityCanBeModified(
            $event
        );


        $eligibilityRule =
            EventEligibilityRule::where(
                'event_id',
                $event->event_id
            )
                ->where(
                    'eligibility_rule_id',
                    $rule
                )
                ->firstOrFail();


        $newlyEligible = DB::transaction(
            function () use (
                $eligibilityRule,
                $event
            ) {

                $eligibilityRule->delete();

                return $this->regenerateEligibleStudents(
                    $event
                );
            }
        );


        $emailNotice = $this->queueEligibilityEmails($event, $newlyEligible);

        return redirect()
            ->route(
                'events.eligibility.index',
                $event->event_id
            )
            ->with(
                'success',
                'Eligibility rule removed successfully.'
            )
            ->with('eligibility_email_notice', $emailNotice);
    }


    /*
    |--------------------------------------------------------------------------
    | Refresh Eligible Student List
    |--------------------------------------------------------------------------
    */

    public function refresh(
        string $event
    ) {

        $event =
            $this->accessibleEvent(
                $event
            );


        $this->ensureEligibilityCanBeModified(
            $event
        );


        $newlyEligible = DB::transaction(
            function () use ($event) {

                return $this->regenerateEligibleStudents(
                    $event
                );
            }
        );


        $emailNotice = $this->queueEligibilityEmails($event, $newlyEligible);

        return redirect()
            ->route(
                'events.eligibility.index',
                $event->event_id
            )
            ->with(
                'success',
                'Eligible student list refreshed from the current student registry.'
            )
            ->with('eligibility_email_notice', $emailNotice);
    }


    /*
    |--------------------------------------------------------------------------
    | Regenerate Eligible Students
    |--------------------------------------------------------------------------
    */

    private function regenerateEligibleStudents(
        Event $event
    ): array {

        // Snapshot BEFORE delete/reinsert. Regeneration itself must not
        // trigger duplicate mail for students already eligible.
        $previous = EventEligibleStudent::query()
            ->where('event_id', $event->event_id)
            ->pluck('participation_type', 'student_id')
            ->all();

        $rules =
            EventEligibilityRule::where(
                'event_id',
                $event->event_id
            )
                ->get();


        $generatedStudents = [];


        foreach ($rules as $rule) {


            /*
             * Specific Student Rule
             */
            if (
                $rule->rule_type
                === 'Specific'
            ) {

                $student =
                    DepartmentAccess::scopeStudentsForDepartment(Student::where('student_id', $rule->student_id), $event->department)
                        ->where(
                            'status',
                            'Active'
                        )
                        ->first();


                if (!$student) {
                    continue;
                }


                $currentType =
                    $generatedStudents[
                        $student->student_id
                    ]
                    ?? null;


                if (
                    $currentType === null
                    ||
                    (
                        $currentType === 'Optional'
                        &&
                        $rule->participation_type
                        === 'Required'
                    )
                ) {

                    $generatedStudents[
                        $student->student_id
                    ] =
                        $rule->participation_type;
                }


                continue;
            }


            /*
             * Criteria Rule
             */
            $query =
                DepartmentAccess::scopeStudentsForDepartment(Student::where('status', 'Active'), $event->department);


            if ($rule->college) {

                $query->where(
                    'college',
                    $rule->college
                );
            }


            if ($rule->program_code) {

                $query->where(
                    'program_code',
                    $rule->program_code
                );
            }


            if ($rule->year_level) {

                $query->where(
                    'year_level',
                    $rule->year_level
                );
            }


            $studentIds =
                $query->pluck(
                    'student_id'
                );


            foreach (
                $studentIds
                as $studentId
            ) {

                $currentType =
                    $generatedStudents[
                        $studentId
                    ]
                    ?? null;


                /*
                 * Required overrides Optional.
                 */
                if (
                    $currentType === null
                    ||
                    (
                        $currentType === 'Optional'
                        &&
                        $rule->participation_type
                        === 'Required'
                    )
                ) {

                    $generatedStudents[
                        $studentId
                    ] =
                        $rule->participation_type;
                }
            }
        }


        EventEligibleStudent::where(
            'event_id',
            $event->event_id
        )
            ->delete();


        if (empty($generatedStudents)) {
            return [];
        }


        $now =
            now();


        $records = [];


        foreach (
            $generatedStudents
            as $studentId => $participationType
        ) {

            $records[] = [

                'event_id' =>
                    $event->event_id,

                'student_id' =>
                    $studentId,

                'participation_type' =>
                    $participationType,

                'created_at' =>
                    $now,

                'updated_at' =>
                    $now,

            ];
        }


        foreach (
            array_chunk(
                $records,
                500
            )
            as $chunk
        ) {

            EventEligibleStudent::insert(
                $chunk
            );
        }

        // Notify only newly included students or students whose requirement
        // changed (Optional <-> Required), never on unchanged refreshes.
        return array_filter(
            $generatedStudents,
            fn ($type, $studentId) => !array_key_exists($studentId, $previous)
                || $previous[$studentId] !== $type,
            ARRAY_FILTER_USE_BOTH
        );
    }


    /**
     * Queue notification emails only AFTER eligibility changes commit.
     * Sending failures must never roll back valid eligibility changes.
     * Uses the configured queue connection: sync for local testing, a worker for database queues.
     */
    private function queueEligibilityEmails(Event $event, array $changes): string
    {
        if (!$changes) {
            return 'No new or changed eligibility. No duplicate emails were queued.';
        }

        $queued = 0;
        $skipped = 0;
        $failed = 0;

        $students = Student::query()
            ->with('user:user_id,student_id,email')
            ->whereIn('student_id', array_keys($changes))
            ->get(['student_id', 'first_name', 'last_name', 'email']);

        foreach ($students as $student) {
            // Prefer the student registry address; fall back to a linked
            // student-account address when the registry email is unusable.
            $email = collect([$student->email, $student->user?->email])
                ->map(fn ($candidate) => trim((string) $candidate))
                ->first(function ($candidate): bool {
                    if (!filter_var($candidate, FILTER_VALIDATE_EMAIL)) {
                        return false;
                    }

                    // Reserved example/test addresses are syntactically valid but
                    // should never receive real SMTP notifications.
                    $domain = strtolower(substr(strrchr($candidate, '@'), 1));

                    return !in_array($domain, [
                        'example.com', 'example.net', 'example.org', 'localhost',
                    ], true) && !preg_match('/\.(?:example|invalid|test|localhost)$/i', $domain);
                });
            if (!$email) {
                $skipped++;
                continue;
            }

            try {
                Mail::to($email)->queue(
                    (new EventEligibilityMail(
                        recipientName: trim($student->first_name.' '.$student->last_name),
                        eventName: (string) $event->event_name,
                        eventDate: $event->event_date?->format('F j, Y') ?? '',
                        startTime: $event->start_time
                            ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : '',
                        endTime: $event->end_time
                            ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : '',
                        venue: (string) ($event->location ?: 'To be announced'),
                        participationType: $changes[$student->student_id],
                        portalUrl: route('student.registrations.index')
                    ))
                );
                $queued++;
            } catch (\Throwable $exception) {
                $failed++;
                Log::error('Could not queue event eligibility email', [
                    'event_id' => $event->event_id,
                    'student_id' => $student->student_id,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }

        return "Eligibility notifications submitted: {$queued}. Missing, invalid or placeholder emails skipped: {$skipped}. Errors: {$failed}.";
    }


    /*
    |--------------------------------------------------------------------------
    | Accessible Event
    |--------------------------------------------------------------------------
    */

    private function accessibleEvent(
        string $event
    ): Event {

        $this->authorizeEligibilityManagement();


        $user =
            auth()->user();


        $event =
            Event::with([
                'department',
                'creator',
            ])
                ->where(
                    'event_id',
                    $event
                )
                ->when(
    $user->role->role_name === 'Department Staff',
    fn ($query) => $query->where(
        'department_id',
        $user->department_id
    )
)
                ->firstOrFail();


        $event->syncScheduledStatus();


        return $event;
    }


    /*
    |--------------------------------------------------------------------------
    | Modification Check
    |--------------------------------------------------------------------------
    */

    private function ensureEligibilityCanBeModified(
        Event $event
    ): void {

        // This guard applies to POST and DELETE endpoints as well as the UI.
        // Even a forged request from an Administrator must be rejected.
        if (auth()->user()?->role?->role_name !== 'Department Staff') {
            abort(403, 'Only authorized Department Staff may modify participant eligibility.');
        }

        if (
            $event->status
            !== 'Upcoming'
        ) {

            throw ValidationException::withMessages([

                'eligibility' =>
                    'Participant eligibility can only be modified while the event is Upcoming.',

            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */
private function authorizeEligibilityManagement(): void
{
    $user = auth()->user();

    $role = $user->role->role_name ?? null;

    if (!in_array($role, ['Administrator', 'Department Staff'], true)) {
        abort(403, 'You are not authorized to manage participant eligibility.');
    }

    if ($role === 'Department Staff' && !$user->department_id) {
        abort(403, 'Your account is not assigned to a department.');
    }
}

}