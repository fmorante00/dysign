<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffAttendanceParticipationReportController extends Controller
{
    public function index(Request $request): View
    {
        $departmentId = $request->user()->department_id;

        abort_unless(
            $departmentId,
            403,
            'No department is linked to this account.'
        );

        // Only events belonging to this department.
        $events = Event::query()
            ->where('department_id', $departmentId)
            ->orderByDesc('event_date')
            ->get(['event_id', 'event_name', 'event_date', 'status']);

        $filters = $request->validate([
            'event_id' => [
                'nullable',
                'integer',
                Rule::exists('events', 'event_id')
                    ->where('department_id', $departmentId),
            ],
            'status' => [
                'nullable',
                Rule::in(['all', 'Present', 'Late']),
            ],
        ]);

        $selectedEvent = $filters['event_id'] ?? null;
        $selectedStatus = $filters['status'] ?? 'all';

        /*
        |--------------------------------------------------------------------------
        | Attendance Report
        |--------------------------------------------------------------------------
        */

        $attendanceQuery = DB::table('attendance_records as ar')
            ->join('events as e', 'e.event_id', '=', 'ar.event_id')
            ->join('students as s', 's.student_id', '=', 'ar.student_id')
            ->where('e.department_id', $departmentId)
            ->when($selectedEvent, function ($query) use ($selectedEvent) {
                $query->where('ar.event_id', $selectedEvent);
            })
            ->when($selectedStatus !== 'all', function ($query) use ($selectedStatus) {
                $query->where('ar.status', $selectedStatus);
            });

        $attendanceSummary = (clone $attendanceQuery)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN ar.status = 'Present' THEN 1 ELSE 0 END), 0) as present"
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN ar.status = 'Late' THEN 1 ELSE 0 END), 0) as late"
            )
            ->first();

        $totalAttendance = (int) $attendanceSummary->total;
        $totalPresent = (int) $attendanceSummary->present;
        $totalLate = (int) $attendanceSummary->late;

        $onTimeRate = $totalAttendance > 0
            ? round(($totalPresent / $totalAttendance) * 100, 1)
            : 0;

        $attendanceRecords = (clone $attendanceQuery)
            ->select(
                'ar.attendance_id',
                'ar.time_in',
                'ar.status',
                's.student_number',
                's.first_name',
                's.last_name',
                'e.event_name',
                'e.event_date'
            )
            ->orderByDesc('ar.time_in')
            ->paginate(15, ['*'], 'attendance_page')
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Participation Report
        | Completed eligible events only.
        |--------------------------------------------------------------------------
        */

        $eligible = DB::table('event_eligible_students as es')
            ->join('events as e', 'e.event_id', '=', 'es.event_id')
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->when($selectedEvent, function ($query) use ($selectedEvent) {
                $query->where('e.event_id', $selectedEvent);
            })
            ->select('es.student_id')
            ->selectRaw('COUNT(DISTINCT es.event_id) as eligible_count')
            ->groupBy('es.student_id');

        $attended = DB::table('attendance_records as ar')
            ->join('events as e', 'e.event_id', '=', 'ar.event_id')
            ->join('event_eligible_students as es', function ($join) {
                $join->on('es.event_id', '=', 'ar.event_id')
                     ->on('es.student_id', '=', 'ar.student_id');
            })
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->whereIn('ar.status', ['Present', 'Late'])
            ->when($selectedEvent, function ($query) use ($selectedEvent) {
                $query->where('e.event_id', $selectedEvent);
            })
            ->select('ar.student_id')
            ->selectRaw('COUNT(DISTINCT ar.event_id) as attended_count')
            ->groupBy('ar.student_id');

        $points = DB::table('activity_points as ap')
            ->join('events as e', 'e.event_id', '=', 'ap.event_id')
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->when($selectedEvent, function ($query) use ($selectedEvent) {
                $query->where('e.event_id', $selectedEvent);
            })
            ->select('ap.student_id')
            ->selectRaw('SUM(ap.points_awarded) as total_points')
            ->groupBy('ap.student_id');

        $participationRecords = DB::table('students as s')
            ->joinSub($eligible, 'eligible', function ($join) {
                $join->on('eligible.student_id', '=', 's.student_id');
            })
            ->leftJoinSub($attended, 'attended', function ($join) {
                $join->on('attended.student_id', '=', 's.student_id');
            })
            ->leftJoinSub($points, 'points', function ($join) {
                $join->on('points.student_id', '=', 's.student_id');
            })
            ->where('s.status', 'Active')
            ->select(
                's.student_number',
                's.first_name',
                's.last_name',
                's.program_code',
                'eligible.eligible_count'
            )
            ->selectRaw(
                'COALESCE(attended.attended_count, 0) as attended_count'
            )
            ->selectRaw(
                'COALESCE(points.total_points, 0) as total_points'
            )
            ->selectRaw(
                'COALESCE(ROUND(100.0 * COALESCE(attended.attended_count, 0) / NULLIF(eligible.eligible_count, 0), 1), 0) as participation_rate'
            )
            ->orderBy('s.last_name')
            ->orderBy('s.first_name')
            ->paginate(15, ['*'], 'students_page')
            ->withQueryString();

        return view(
            'staff.reports.index',
            compact(
                'events',
                'selectedEvent',
                'selectedStatus',
                'totalAttendance',
                'totalPresent',
                'totalLate',
                'onTimeRate',
                'attendanceRecords',
                'participationRecords'
            )
        );
    }
}
