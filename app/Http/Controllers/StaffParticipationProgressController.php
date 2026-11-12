<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StaffParticipationProgressController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Department Access
        |--------------------------------------------------------------------------
        */

        $departmentId = $request->user()->department_id;

        abort_unless(
            $departmentId,
            403,
            'No department is linked to this account.'
        );

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($validated['search'] ?? '');

        /*
        |--------------------------------------------------------------------------
        | Completed Department Events
        |--------------------------------------------------------------------------
        */

        $totalCompletedEvents = Event::query()
            ->where('department_id', $departmentId)
            ->where('status', 'Completed')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Eligible Events Per Student
        |--------------------------------------------------------------------------
        */

        $eligible = DB::table('event_eligible_students as es')
            ->join(
                'events as e',
                'e.event_id',
                '=',
                'es.event_id'
            )
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->select('es.student_id')
            ->selectRaw(
                'COUNT(DISTINCT es.event_id) as eligible_count'
            )
            ->groupBy('es.student_id');

        /*
        |--------------------------------------------------------------------------
        | Attended Eligible Events Per Student
        |--------------------------------------------------------------------------
        */

        $attended = DB::table('attendance_records as ar')
            ->join(
                'events as e',
                'e.event_id',
                '=',
                'ar.event_id'
            )
            ->join('event_eligible_students as es', function ($join) {
                $join->on('es.event_id', '=', 'ar.event_id')
                    ->on('es.student_id', '=', 'ar.student_id');
            })
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->whereIn('ar.status', ['Present', 'Late'])
            ->select('ar.student_id')
            ->selectRaw(
                'COUNT(DISTINCT ar.event_id) as attended_count'
            )
            ->groupBy('ar.student_id');

        /*
        |--------------------------------------------------------------------------
        | Activity Points Per Student
        |--------------------------------------------------------------------------
        */

        $points = DB::table('activity_points as ap')
            ->join(
                'events as e',
                'e.event_id',
                '=',
                'ap.event_id'
            )
            ->where('e.department_id', $departmentId)
            ->select('ap.student_id')
            ->selectRaw(
                'SUM(ap.points_awarded) as total_points'
            )
            ->groupBy('ap.student_id');

        /*
        |--------------------------------------------------------------------------
        | Student Participation Progress
        |--------------------------------------------------------------------------
        */

        $query = DB::table('students as s')
            ->joinSub($eligible, 'eligible', function ($join) {
                $join->on(
                    'eligible.student_id',
                    '=',
                    's.student_id'
                );
            })
            ->leftJoinSub($attended, 'attended', function ($join) {
                $join->on(
                    'attended.student_id',
                    '=',
                    's.student_id'
                );
            })
            ->leftJoinSub($points, 'points', function ($join) {
                $join->on(
                    'points.student_id',
                    '=',
                    's.student_id'
                );
            })
            ->where('s.status', 'Active')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where(
                        's.student_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        's.first_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        's.last_name',
                        'like',
                        "%{$search}%"
                    );
                });
            });

        /*
        |--------------------------------------------------------------------------
        | Paginated Progress Records
        |--------------------------------------------------------------------------
        */

        $students = $query
            ->select(
                's.student_id',
                's.student_number',
                's.first_name',
                's.last_name',
                's.program_code',
                's.year_level',
                'eligible.eligible_count'
            )
            ->selectRaw(
                'COALESCE(attended.attended_count, 0) as attended_count'
            )
            ->selectRaw(
                'COALESCE(points.total_points, 0) as total_points'
            )
            ->selectRaw(
                'COALESCE(
                    ROUND(
                        100.0 * COALESCE(attended.attended_count, 0)
                        / NULLIF(eligible.eligible_count, 0),
                        1
                    ),
                    0
                ) as progress_percentage'
            )
            ->orderBy('s.last_name')
            ->orderBy('s.first_name')
            ->paginate(15)
            ->withQueryString();

        $totalStudents = $students->total();

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'staff.participation-progress.index',
            compact(
                'students',
                'search',
                'totalStudents',
                'totalCompletedEvents'
            )
        );
    }
}
