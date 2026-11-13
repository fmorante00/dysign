<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentAttendanceHistoryController extends Controller
{
    /**
     * Student attendance history: read-only view of persisted attendance records.
     * Absent and Excused records must be created by the appropriate official
     * finalization/approval workflow, never inferred by this controller.
     */
    public function index(Request $request): View
    {
        $student = $request->user()->student;

        abort_unless(
            $student,
            403,
            'No student record is linked to this account.'
        );

        $studentId = $student->student_id;

        // Match the six statuses supported by the attendance_records migration.
        $statuses = [
            'Checked In',
            'Present',
            'Late',
            'Incomplete',
            'Absent',
            'Excused',
        ];

        $validated = $request->validate([
            'status' => [
                'nullable',
                Rule::in(array_merge(['all'], $statuses)),
            ],
        ]);

        $selectedStatus = $validated['status'] ?? 'all';

        // Aggregate by status in one query, independent of the selected filter.
        $statusCounts = array_fill_keys($statuses, 0);

        $rows = AttendanceRecord::query()
            ->where('student_id', $studentId)
            ->select('status')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('status')
            ->get();

        foreach ($rows as $row) {
            if (array_key_exists($row->status, $statusCounts)) {
                $statusCounts[$row->status] = (int) $row->total;
            }
        }

        // The number of saved rows, NOT the number of events the student
        // was expected to attend. Missing scans are not made Absent here.
        $totalAttendance = AttendanceRecord::query()
            ->where('student_id', $studentId)
            ->count();

        $totalCheckedIn = $statusCounts['Checked In'];
        $totalPresent = $statusCounts['Present'];
        $totalLate = $statusCounts['Late'];
        $totalIncomplete = $statusCounts['Incomplete'];
        $totalAbsent = $statusCounts['Absent'];
        $totalExcused = $statusCounts['Excused'];

        // Count the share marked Present among records finalized as Present/Late.
        // Do not include temporary Checked In or Incomplete/Absent/Excused rows.
        $completedCount = $totalPresent + $totalLate;
        $onTimeRate = $completedCount > 0
            ? round(($totalPresent / $completedCount) * 100, 1)
            : 0;

        $attendanceRecords = AttendanceRecord::query()
            ->with('event')
            ->where('student_id', $studentId)
            ->when(
                $selectedStatus !== 'all',
                fn ($query) => $query->where('status', $selectedStatus)
            )
            ->orderByDesc('time_in')
            ->orderByDesc('attendance_id')
            ->paginate(10)
            ->withQueryString();

        return view('student.attendance-history.index', compact(
            'student',
            'attendanceRecords',
            'selectedStatus',
            'statusCounts',
            'totalAttendance',
            'totalCheckedIn',
            'totalPresent',
            'totalLate',
            'totalIncomplete',
            'totalAbsent',
            'totalExcused',
            'onTimeRate'
        ));
    }
}
