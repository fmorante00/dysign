<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventEligibilityRule;
use App\Models\EventEligibleStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventEligibilityController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Eligibility Module Event List
    |--------------------------------------------------------------------------
    */

    public function events()
    {
        $this->authorizeEligibilityManagement();

        Event::syncScheduledStatuses();

        $user =
            auth()->user();

        $events = Event::with([
            'department',
            'creator',
        ])
            ->withCount([
                'eligibilityRules',
                'eligibleStudents',
            ])
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
            'events.eligibility-events',
            compact('events')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Eligibility Configuration
    |--------------------------------------------------------------------------
    */

    public function index(
        string $event
    ) {

        $event =
            $this->accessibleEvent(
                $event
            );


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
            Student::where(
                'status',
                'Active'
            )
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
            Student::where(
                'status',
                'Active'
            )
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
            Student::where(
                'status',
                'Active'
            )
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
            Student::where(
                'status',
                'Active'
            )
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
                Student::where(
                    'status',
                    'Active'
                )
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
                Student::where(
                    'status',
                    'Active'
                )
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
                Student::where(
                    'status',
                    'Active'
                )
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
            Student::where(
                'status',
                'Active'
            );


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


        DB::transaction(
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


                $this->regenerateEligibleStudents(
                    $event
                );
            }
        );


        return redirect()
            ->route(
                'events.eligibility.index',
                $event->event_id
            )
            ->with(
                'success',
                'Eligibility criteria saved and the eligible student list was regenerated.'
            );
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
            Student::where(
                'student_id',
                $validated['student_id']
            )
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


        DB::transaction(
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


                $this->regenerateEligibleStudents(
                    $event
                );
            }
        );


        return redirect()
            ->route(
                'events.eligibility.index',
                $event->event_id
            )
            ->with(
                'success',
                'Specific student eligibility saved successfully.'
            );
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


        DB::transaction(
            function () use (
                $eligibilityRule,
                $event
            ) {

                $eligibilityRule->delete();

                $this->regenerateEligibleStudents(
                    $event
                );
            }
        );


        return redirect()
            ->route(
                'events.eligibility.index',
                $event->event_id
            )
            ->with(
                'success',
                'Eligibility rule removed successfully.'
            );
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


        DB::transaction(
            function () use ($event) {

                $this->regenerateEligibleStudents(
                    $event
                );
            }
        );


        return redirect()
            ->route(
                'events.eligibility.index',
                $event->event_id
            )
            ->with(
                'success',
                'Eligible student list refreshed from the current student registry.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Regenerate Eligible Students
    |--------------------------------------------------------------------------
    */

    private function regenerateEligibleStudents(
        Event $event
    ): void {

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
                    Student::where(
                        'student_id',
                        $rule->student_id
                    )
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
                Student::where(
                    'status',
                    'Active'
                );


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
            return;
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
                'eligibilityRules',
                'eligibleStudents.student',
            ])
                ->where(
                    'event_id',
                    $event
                )
                ->where(
                    'department_id',
                    $user->department_id
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
        $user =
            auth()->user();


        $role =
            $user->role->role_name
            ?? null;


        if (
            $role
            !== 'Department Staff'
        ) {

            abort(
                403,
                'Only Department Staff can manage participant eligibility.'
            );
        }


        if (
            !$user->department_id
        ) {

            abort(
                403,
                'Your account is not assigned to a department.'
            );
        }
    }
}