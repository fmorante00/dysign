<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentAttendanceHistoryController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Authenticated Student
        |--------------------------------------------------------------------------
        */

        $student = $request->user()->student;

        abort_unless(
            $student,
            403,
            'No student record is linked to this account.'
        );

        $studentId = $student->student_id;

        /*
        |--------------------------------------------------------------------------
        | Attendance Status Filter
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'status' => [
                'nullable',
                Rule::in(['all', 'Present', 'Late']),
            ],
        ]);

        $selectedStatus = $validated['status'] ?? 'all';

        /*
        |--------------------------------------------------------------------------
        | Attendance Summary
        |--------------------------------------------------------------------------
        */

        $totalAttendance = AttendanceRecord::query()
            ->where('student_id', $studentId)
            ->count();

        $totalPresent = AttendanceRecord::query()
            ->where('student_id', $studentId)
            ->where('status', 'Present')
            ->count();

        $totalLate = AttendanceRecord::query()
            ->where('student_id', $studentId)
            ->where('status', 'Late')
            ->count();

        $onTimeRate = $totalAttendance > 0
            ? round(
                ($totalPresent / $totalAttendance) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Student Attendance History
        |--------------------------------------------------------------------------
        |
        | Students can only view their own attendance records.
        |
        */

        $attendanceRecords = AttendanceRecord::query()
            ->with('event')
            ->where('student_id', $studentId)
            ->when(
                $selectedStatus !== 'all',
                function ($query) use ($selectedStatus) {
                    $query->where('status', $selectedStatus);
                }
            )
            ->orderByDesc('time_in')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'student.attendance-history.index',
            compact(
                'student',
                'attendanceRecords',
                'selectedStatus',
                'totalAttendance',
                'totalPresent',
                'totalLate',
                'onTimeRate'
            )
        );
    }
}
