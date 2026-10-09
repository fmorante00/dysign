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
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = 20;

        $currentPage =
            LengthAwarePaginator::resolveCurrentPage();


        $currentRecords =
            $evaluations
                ->forPage(
                    $currentPage,
                    $perPage
                )
                ->values();


        $records = new LengthAwarePaginator(

            $currentRecords,

            $evaluations->count(),

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

                'lowParticipationCount'
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