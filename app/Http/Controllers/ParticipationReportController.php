<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipationReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Report State
        |--------------------------------------------------------------------------
        */

        $generated = $request->boolean('generated');


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $programs = DB::table('students')
            ->where('status', 'Active')
            ->select(
                'program_code',
                'program_name'
            )
            ->distinct()
            ->orderBy('program_code')
            ->get();


        $yearLevels = DB::table('students')
            ->where('status', 'Active')
            ->select('year_level')
            ->distinct()
            ->orderBy('year_level')
            ->pluck('year_level');


        /*
        |--------------------------------------------------------------------------
        | Total Recorded Events
        |--------------------------------------------------------------------------
        |
        | Currently DySign does not yet have an event eligibility table.
        |
        | Because of that, the current participation formula uses all events
        | that already contain attendance records.
        |
        */

        $totalRecordedEvents = DB::table(
            'attendance_records'
        )
        ->distinct()
        ->count('event_id');


        /*
        |--------------------------------------------------------------------------
        | Student Participation Data
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

                DB::raw(
                    'COUNT(DISTINCT ar.event_id) as events_attended'
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
            ])

            ->orderBy('s.last_name')
            ->orderBy('s.first_name')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Participation Rate
        |--------------------------------------------------------------------------
        */

        $evaluations = $studentMetrics
            ->map(function ($student) use ($totalRecordedEvents) {

                $student->student_name = trim(
                    $student->first_name
                    . ' '
                    . (
                        $student->middle_name
                            ? $student->middle_name . ' '
                            : ''
                    )
                    . $student->last_name
                );


                $student->events_attended =
                    (int) $student->events_attended;


                $student->total_applicable_events =
                    $totalRecordedEvents;


                /*
                |--------------------------------------------------------------------------
                | Participation Percentage
                |--------------------------------------------------------------------------
                */

                if ($totalRecordedEvents > 0) {

                    $student->participation_rate = round(
                        (
                            $student->events_attended
                            / $totalRecordedEvents
                        ) * 100,
                        2
                    );

                } else {

                    $student->participation_rate = 0;

                }


                /*
                |--------------------------------------------------------------------------
                | Classification
                |--------------------------------------------------------------------------
                */

                if ($totalRecordedEvents === 0) {

                    $student->classification =
                        'No Data';

                } elseif (
                    $student->participation_rate >= 90
                ) {

                    $student->classification =
                        'Highly Participative';

                } elseif (
                    $student->participation_rate >= 75
                ) {

                    $student->classification =
                        'Participative';

                } elseif (
                    $student->participation_rate >= 60
                ) {

                    $student->classification =
                        'Moderately Participative';

                } else {

                    $student->classification =
                        'Low Participation';

                }


                return $student;

            });


        /*
        |--------------------------------------------------------------------------
        | Apply Report Filters
        |--------------------------------------------------------------------------
        */

        $results = $evaluations;


        if ($generated) {

            /*
            |--------------------------------------------------------------------------
            | Program Filter
            |--------------------------------------------------------------------------
            */

            if ($request->filled('program')) {

                $results = $results
                    ->where(
                        'program_code',
                        $request->program
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | Year Level Filter
            |--------------------------------------------------------------------------
            */

            if ($request->filled('year_level')) {

                $results = $results
                    ->filter(function ($student) use ($request) {

                        return
                            (string) $student->year_level
                            ===
                            (string) $request->year_level;

                    });

            }


            /*
            |--------------------------------------------------------------------------
            | Classification Filter
            |--------------------------------------------------------------------------
            */

            if ($request->filled('classification')) {

                $results = $results
                    ->where(
                        'classification',
                        $request->classification
                    );

            }

        }


        $results = $results
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        |
        | Before a report is generated, the summary represents all students.
        |
        | Once filters are applied, the summary follows the selected filters.
        |
        */

        $summarySource =
            $generated
                ? $results
                : $evaluations;


        $summary = [

            'total' =>
                $totalRecordedEvents > 0
                    ? $summarySource->count()
                    : 0,

            'highly' =>
                $summarySource
                    ->where(
                        'classification',
                        'Highly Participative'
                    )
                    ->count(),

            'participative' =>
                $summarySource
                    ->where(
                        'classification',
                        'Participative'
                    )
                    ->count(),

            'moderately' =>
                $summarySource
                    ->where(
                        'classification',
                        'Moderately Participative'
                    )
                    ->count(),

            'low' =>
                $summarySource
                    ->where(
                        'classification',
                        'Low Participation'
                    )
                    ->count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'reports.participation',
            compact(
                'summary',
                'programs',
                'yearLevels',
                'generated',
                'results',
                'totalRecordedEvents'
            )
        );
    }
}