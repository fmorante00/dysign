<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipationRecordsController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalStudents = Student::where(
            'status',
            'Active'
        )->count();


        $participatingStudents = DB::table(
                'attendance_records as ar'
            )
            ->join(
                'students as s',
                's.student_id',
                '=',
                'ar.student_id'
            )
            ->where(
                's.status',
                'Active'
            )
            ->distinct()
            ->count(
                'ar.student_id'
            );


        $eventsRecorded = AttendanceRecord::query()
            ->distinct()
            ->count(
                'event_id'
            );


        $attendanceEntries = AttendanceRecord::count();


        /*
        |--------------------------------------------------------------------------
        | Participation Records
        |--------------------------------------------------------------------------
        |
        | LEFT JOIN is intentional.
        |
        | This allows active students with zero attendance records to
        | still appear in the participation table.
        |
        */

        $records = DB::table('students as s')
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

            ->orderByDesc(
                'events_attended'
            )

            ->orderBy(
                's.last_name'
            )

            ->orderBy(
                's.first_name'
            )

            ->paginate(20)
            ->withQueryString();


        return view(
            'participation.records',
            compact(
                'records',
                'totalStudents',
                'participatingStudents',
                'eventsRecorded',
                'attendanceEntries'
            )
        );
    }
}