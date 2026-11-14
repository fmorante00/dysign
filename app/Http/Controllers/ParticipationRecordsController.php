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

        // Filter only the student rows; keep the overview KPIs campus-wide.
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'college' => ['nullable', 'string', 'max:150'],
            'program' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'string', 'max:20'],
            'participation' => ['nullable', 'in:all,with_records,without_records'],
            'sort' => ['nullable', 'in:most_events,recent,name'],
            'per_page' => ['nullable', 'in:10,25,50'],
        ]);

        $search = trim($filters['search'] ?? '');
        $college = $filters['college'] ?? '';
        $program = $filters['program'] ?? '';
        $year = $filters['year'] ?? '';
        $participation = $filters['participation'] ?? 'all';
        $sort = $filters['sort'] ?? 'most_events';
        $perPage = (int) ($filters['per_page'] ?? 10);

        $activeStudents = Student::query()->where('status', 'Active');
        $colleges = (clone $activeStudents)->whereNotNull('college')
            ->where('college', '<>', '')->distinct()->orderBy('college')->pluck('college');
        $programs = (clone $activeStudents)->whereNotNull('program_code')
            ->where('program_code', '<>', '')->distinct()->orderBy('program_code')->pluck('program_code');
        $years = (clone $activeStudents)->whereNotNull('year_level')
            ->distinct()->orderBy('year_level')->pluck('year_level');

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
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('s.student_number', 'like', $like)
                        ->orWhere('s.first_name', 'like', $like)
                        ->orWhere('s.middle_name', 'like', $like)
                        ->orWhere('s.last_name', 'like', $like)
                        ->orWhere('s.college', 'like', $like)
                        ->orWhere('s.program_name', 'like', $like)
                        ->orWhere('s.program_code', 'like', $like)
                        ->orWhereRaw("CONCAT_WS(' ', s.first_name, NULLIF(s.middle_name, ''), s.last_name) LIKE ?", [$like]);
                });
            })
            ->when($college !== '', fn ($q) => $q->where('s.college', $college))
            ->when($program !== '', fn ($q) => $q->where('s.program_code', $program))
            ->when($year !== '', fn ($q) => $q->where('s.year_level', $year))
            ->when($participation === 'with_records', function ($query) {
                $query->whereExists(function ($q) {
                    $q->selectRaw('1')->from('attendance_records as ar_filter')
                        ->whereColumn('ar_filter.student_id', 's.student_id');
                });
            })
            ->when($participation === 'without_records', function ($query) {
                $query->whereNotExists(function ($q) {
                    $q->selectRaw('1')->from('attendance_records as ar_filter')
                        ->whereColumn('ar_filter.student_id', 's.student_id');
                });
            })

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

            ->when($sort === 'most_events', fn ($q) => $q->orderByDesc('events_attended'))
            ->when($sort === 'recent', fn ($q) => $q->orderByDesc('last_participation'))
            ->orderBy('s.last_name')
            ->orderBy('s.first_name')
            ->orderBy('s.student_id')
            ->paginate($perPage)
            ->withQueryString();


        return view(
            'participation.records',
            compact(
                'records',
                'totalStudents',
                'participatingStudents',
                'eventsRecorded',
                'attendanceEntries',
                'colleges',
                'programs',
                'years',
                'search',
                'college',
                'program',
                'year',
                'participation',
                'sort',
                'perPage'
            )
        );
    }
}