<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentParticipationClassificationController extends Controller
{
    public function index(Request $request): View
    {
        $departmentId = $request->user()->department_id;

        abort_unless($departmentId, 403);

        $search = trim((string) $request->query('search', ''));

        // Count completed events each student was eligible for.
        $eligible = DB::table('event_eligible_students as es')
            ->join('events as e', 'e.event_id', '=', 'es.event_id')
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->select('es.student_id')
            ->selectRaw('COUNT(DISTINCT es.event_id) as eligible_count')
            ->groupBy('es.student_id');

        // Count attended events (Present or Late).
        $attended = DB::table('attendance_records as ar')
            ->join('events as e', 'e.event_id', '=', 'ar.event_id')
            ->join('event_eligible_students as es', function ($join) {
                $join->on('es.event_id', '=', 'ar.event_id')
                     ->on('es.student_id', '=', 'ar.student_id');
            })
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->whereIn('ar.status', ['Present', 'Late'])
            ->select('ar.student_id')
            ->selectRaw('COUNT(DISTINCT ar.event_id) as attended_count')
            ->groupBy('ar.student_id');

        // Total earned points for the department's events.
        $points = DB::table('activity_points as ap')
            ->join('events as e', 'e.event_id', '=', 'ap.event_id')
            ->where('e.department_id', $departmentId)
            ->select('ap.student_id')
            ->selectRaw('SUM(ap.points_awarded) as total_points')
            ->groupBy('ap.student_id');

        $students = Student::query()
            ->joinSub($eligible, 'eligible', function ($join) {
                $join->on('eligible.student_id', '=', 'students.student_id');
            })
            ->leftJoinSub($attended, 'attended', function ($join) {
                $join->on('attended.student_id', '=', 'students.student_id');
            })
            ->leftJoinSub($points, 'points', function ($join) {
                $join->on('points.student_id', '=', 'students.student_id');
            })
            ->where('students.status', 'Active')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('students.student_number', 'like', "%{$search}%")
                      ->orWhere('students.first_name', 'like', "%{$search}%")
                      ->orWhere('students.last_name', 'like', "%{$search}%");
                });
            })
            ->select(
                'students.student_id',
                'students.student_number',
                'students.first_name',
                'students.last_name',
                'students.program_code',
                'eligible.eligible_count'
            )
            ->selectRaw('COALESCE(attended.attended_count, 0) as attended_count')
            ->selectRaw('COALESCE(points.total_points, 0) as total_points')
            ->orderBy('students.last_name')
            ->orderBy('students.first_name')
            ->paginate(15)
            ->withQueryString();

        // Calculate classification dynamically.
        $students->getCollection()->transform(function ($student) {

            $eligibleCount = (int) $student->eligible_count;
            $attendedCount = (int) $student->attended_count;

            $rate = $eligibleCount > 0
                ? round(($attendedCount / $eligibleCount) * 100, 1)
                : 0;

            $student->participation_rate = $rate;

            $student->classification = match (true) {
                $eligibleCount === 0 => 'No Data',
                $rate >= 80 => 'Active Participation',
                $rate >= 50 => 'Moderate Participation',
                default => 'Low Participation',
            };

            return $student;
        });

        return view(
            'staff.participation-classifications.index',
            compact('students', 'search')
        );
    }
}
