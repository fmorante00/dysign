<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentAttendanceHistoryController extends Controller
{
    /**
     * Student attendance history: read-only view of persisted attendance records.
     * Absent and Excused records must be created by the appropriate official
     * finalization/approval workflow, never inferred by this controller.
     */
    public function index(Request $request): View|RedirectResponse
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

        $validator = \Illuminate\Support\Facades\Validator::make($request->query(), [
            'status' => ['nullable', Rule::in(array_merge(['all'], $statuses))],
            'q' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'recently_updated'])],
            'per_page' => ['nullable', Rule::in(['5', '10', '20'])],
        ]);
        if ($validator->fails()) {
            return redirect()->route('student.attendance-history.index')->withErrors($validator);
        }
        $validated = $validator->validated();

        $selectedStatus = $validated['status'] ?? 'all';
        $searchTerm = trim($validated['q'] ?? '');
        $fromDate = $validated['from'] ?? null;
        $toDate = $validated['to'] ?? null;
        if ($fromDate && $toDate && $fromDate > $toDate) {
            // A reversed date interval should not silently produce misleading data.
            return redirect()->route('student.attendance-history.index')->withErrors([
                'to' => 'End date must be on or after the start date.',
            ]);
        }
        $selectedSort = $validated['sort'] ?? 'newest';
        $perPage = (int) ($validated['per_page'] ?? 5);

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

        // Search, date range, sorting, and pagination operate on saved records
        // for the authenticated student ONLY. Summary cards remain unfiltered.
        // LEFT JOIN keeps historical rows even if their linked event was removed.
        $historyQuery = AttendanceRecord::query()
            ->with('event')
            ->leftJoin('events', 'events.event_id', '=', 'attendance_records.event_id')
            ->select('attendance_records.*')
            ->where('attendance_records.student_id', $studentId)
            ->when(
                $selectedStatus !== 'all',
                fn ($query) => $query->where('attendance_records.status', $selectedStatus)
            )
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where(function ($matchedEvent) use ($searchTerm) {
                    $matchedEvent->where('events.event_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('events.location', 'like', '%' . $searchTerm . '%');
                });
            })
            ->when($fromDate, fn ($query) => $query->where('events.event_date', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->where('events.event_date', '<=', $toDate));

        if ($selectedSort === 'oldest') {
            $historyQuery->orderBy('events.event_date')
                ->orderBy('attendance_records.time_in')
                ->orderBy('attendance_records.attendance_id');
        } elseif ($selectedSort === 'recently_updated') {
            $historyQuery->orderByDesc('attendance_records.updated_at')
                ->orderByDesc('attendance_records.attendance_id');
        } else {
            // Event date (instead of Time In alone) also orders Incomplete rows
            // which legitimately have no captured scan timestamps.
            $historyQuery->orderByDesc('events.event_date')
                ->orderByDesc('attendance_records.time_in')
                ->orderByDesc('attendance_records.attendance_id');
        }

        $attendanceRecords = $historyQuery
            ->paginate($perPage)
            ->withQueryString()
            ->fragment('attendance-records');

        return view('student.attendance-history.index', compact(
            'student',
            'attendanceRecords',
            'selectedStatus',
            'searchTerm',
            'fromDate',
            'toDate',
            'selectedSort',
            'perPage',
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
