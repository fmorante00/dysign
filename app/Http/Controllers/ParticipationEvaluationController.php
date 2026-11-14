<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ParticipationEvaluationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Evaluation Page
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Total Recorded Events
        |--------------------------------------------------------------------------
        |
        | Only events with at least one attendance record are included.
        |
        */

        $totalRecordedEvents = AttendanceRecord::query()
            ->distinct()
            ->count('event_id');


        /*
        |--------------------------------------------------------------------------
        | Student Participation Metrics
        |--------------------------------------------------------------------------
        */

        $studentMetrics = DB::table('students as s')

            ->leftJoin(
                'attendance_records as ar',
                'ar.student_id',
                '=',
                's.student_id'
            )

            ->where(
                's.status',
                'Active'
            )

            ->select([

                's.student_id',
                's.student_number',

                's.first_name',
                's.middle_name',
                's.last_name',

                's.college',
                's.program_name',
                's.program_code',

                's.year_level',
                's.photo_path',
                's.status',

                DB::raw(
                    'COUNT(DISTINCT ar.event_id) as events_attended'
                ),

                DB::raw(
                    "
                    SUM(
                        CASE
                            WHEN ar.status = 'Present'
                            THEN 1
                            ELSE 0
                        END
                    ) as present_count
                    "
                ),

                DB::raw(
                    "
                    SUM(
                        CASE
                            WHEN ar.status = 'Late'
                            THEN 1
                            ELSE 0
                        END
                    ) as late_count
                    "
                ),

                DB::raw(
                    'MAX(ar.time_in) as last_participation'
                ),

            ])

            ->groupBy([

                's.student_id',
                's.student_number',

                's.first_name',
                's.middle_name',
                's.last_name',

                's.college',
                's.program_name',
                's.program_code',

                's.year_level',
                's.photo_path',
                's.status',

            ])

            ->orderBy(
                's.last_name'
            )

            ->orderBy(
                's.first_name'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Automatic Participation Evaluation
        |--------------------------------------------------------------------------
        */

        $evaluations = $studentMetrics
            ->map(function ($student) use ($totalRecordedEvents) {

                $eventsAttended = (int) $student->events_attended;


                /*
                |--------------------------------------------------------------------------
                | Participation Rate
                |--------------------------------------------------------------------------
                */

                if ($totalRecordedEvents > 0) {

                    $participationRate = round(
                        (
                            $eventsAttended
                            / $totalRecordedEvents
                        ) * 100,
                        2
                    );

                } else {

                    $participationRate = 0;

                }


                /*
                |--------------------------------------------------------------------------
                | Classification
                |--------------------------------------------------------------------------
                */

                if ($totalRecordedEvents === 0) {

                    $classification = 'No Data';

                } elseif ($participationRate >= 90) {

                    $classification = 'Highly Participative';

                } elseif ($participationRate >= 75) {

                    $classification = 'Participative';

                } elseif ($participationRate >= 60) {

                    $classification = 'Moderately Participative';

                } else {

                    $classification = 'Low Participation';

                }


                /*
                |--------------------------------------------------------------------------
                | Attach Calculated Values
                |--------------------------------------------------------------------------
                */

                $student->participation_rate =
                    $participationRate;

                $student->classification =
                    $classification;

                $student->total_recorded_events =
                    $totalRecordedEvents;


                return $student;

            });


        /*
        |--------------------------------------------------------------------------
        | Classification Summary
        |--------------------------------------------------------------------------
        */

        $studentsEvaluated =
            $totalRecordedEvents > 0
                ? $evaluations->count()
                : 0;


        $highlyParticipativeCount =
            $evaluations
                ->where(
                    'classification',
                    'Highly Participative'
                )
                ->count();


        $participativeCount =
            $evaluations
                ->where(
                    'classification',
                    'Participative'
                )
                ->count();


        $moderatelyParticipativeCount =
            $evaluations
                ->where(
                    'classification',
                    'Moderately Participative'
                )
                ->count();


        $lowParticipationCount =
            $evaluations
                ->where(
                    'classification',
                    'Low Participation'
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Search, Filters and Sort (overall classification totals stay unchanged)
        |--------------------------------------------------------------------------
        */

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'college' => ['nullable', 'string', 'max:150'],
            'program' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'string', 'max:20'],
            'classification' => ['nullable', 'in:all,highly,participative,moderately,low,no_data'],
            'sort' => ['nullable', 'in:name,highest_rate,lowest_rate,most_events'],
            'per_page' => ['nullable', 'in:10,25,50'],
        ]);

        $search = trim($filters['search'] ?? '');
        $college = $filters['college'] ?? '';
        $program = $filters['program'] ?? '';
        $year = $filters['year'] ?? '';
        $classification = $filters['classification'] ?? 'all';
        $sort = $filters['sort'] ?? 'name';
        $perPage = (int) ($filters['per_page'] ?? 10);

        // Obtain filter options from the full roster, not just the current page.
        $colleges = $evaluations->pluck('college')->filter(fn ($v) => filled($v))
            ->unique()->sort()->values();
        $programs = $evaluations->pluck('program_code')->filter(fn ($v) => filled($v))
            ->unique()->sort()->values();
        $years = $evaluations->pluck('year_level')->filter(fn ($v) => filled($v) || $v === 0)
            ->unique()->sort()->values();

        $classificationLabels = [
            'highly' => 'Highly Participative',
            'participative' => 'Participative',
            'moderately' => 'Moderately Participative',
            'low' => 'Low Participation',
            'no_data' => 'No Data',
        ];

        $filteredEvaluations = $evaluations->filter(function ($student) use (
            $search, $college, $program, $year, $classification, $classificationLabels
        ) {
            if ($college !== '' && $student->college !== $college) return false;
            if ($program !== '' && $student->program_code !== $program) return false;
            if ($year !== '' && (string) $student->year_level !== (string) $year) return false;
            if ($classification !== 'all'
                && $student->classification !== ($classificationLabels[$classification] ?? null)) return false;

            if ($search !== '') {
                $name = trim(implode(' ', array_filter([
                    $student->first_name, $student->middle_name, $student->last_name,
                ], fn ($part) => filled($part))));
                $haystack = implode(' ', [
                    $student->student_number,
                    $name,
                    $student->college,
                    $student->program_name,
                    $student->program_code,
                ]);
                if (stripos($haystack, $search) === false) return false;
            }

            return true;
        });

        $filteredEvaluations = match ($sort) {
            'highest_rate' => $filteredEvaluations->sortByDesc('participation_rate'),
            'lowest_rate' => $filteredEvaluations->sortBy('participation_rate'),
            'most_events' => $filteredEvaluations->sortByDesc('events_attended'),
            default => $filteredEvaluations, // Already ordered by student surname, first name.
        };

        $filteredEvaluations = $filteredEvaluations->values();

        /*
        |--------------------------------------------------------------------------
        | Pagination (after filters, preserving the active query string)
        |--------------------------------------------------------------------------
        */

        $currentPage =
            LengthAwarePaginator::resolveCurrentPage();


        $currentRecords =
            $filteredEvaluations
                ->forPage(
                    $currentPage,
                    $perPage
                )
                ->values();


        $records = new LengthAwarePaginator(

            $currentRecords,

            $filteredEvaluations->count(),

            $perPage,

            $currentPage,

            [
                'path' =>
                    $request->url(),

                'query' =>
                    $request->query(),
            ]

        );


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'participation.evaluation',
            compact(
                'records',

                'totalRecordedEvents',

                'studentsEvaluated',

                'highlyParticipativeCount',

                'participativeCount',

                'moderatelyParticipativeCount',

                'lowParticipationCount',
                'search',
                'college',
                'program',
                'year',
                'classification',
                'sort',
                'perPage',
                'colleges',
                'programs',
                'years'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Re-Evaluate Students
    |--------------------------------------------------------------------------
    |
    | Evaluations are calculated dynamically from the latest database records.
    | Redirecting back causes all rates and classifications to be recalculated.
    |
    */

    public function reevaluate()
    {
        return redirect()
            ->route(
                'participation.evaluation'
            )
            ->with(
                'success',
                'Student participation classifications were recalculated using the latest attendance records.'
            );
    }
}