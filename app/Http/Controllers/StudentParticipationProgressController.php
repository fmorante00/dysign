<?php

namespace App\Http\Controllers;

use App\Models\ActivityPoint;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentParticipationProgressController extends Controller
{
    public function index(Request $request): View
    {
        // Get the student linked to the logged-in account.
        $student = $request->user()->student;

        abort_unless(
            $student,
            403,
            'No student record is linked to this account.'
        );

        $studentId = $student->student_id;

        // Completed events that the student was eligible to attend.
        $eligibleEventIds = DB::table('event_eligible_students')
            ->join(
                'events',
                'events.event_id',
                '=',
                'event_eligible_students.event_id'
            )
            ->where('event_eligible_students.student_id', $studentId)
            ->where('events.status', 'Completed')
            ->distinct()
            ->pluck('events.event_id');

        $totalEligibleEvents = $eligibleEventIds->count();

        // Completed eligible events with recorded attendance.
        $attendedEvents = AttendanceRecord::query()
            ->where('student_id', $studentId)
            ->whereIn('event_id', $eligibleEventIds->all())
            ->whereIn('status', ['Present', 'Late'])
            ->distinct()
            ->count('event_id');

        // Participation progress percentage.
        $progressPercentage = $totalEligibleEvents > 0
            ? round(($attendedEvents / $totalEligibleEvents) * 100, 1)
            : 0;

        // Total activity points earned.
        $totalPoints = (int) ActivityPoint::query()
            ->where('student_id', $studentId)
            ->sum('points_awarded');

        // Student attendance history.
        $attendanceRecords = AttendanceRecord::with('event')
            ->where('student_id', $studentId)
            ->orderByDesc('time_in')
            ->paginate(10);

        // Recent awarded activity points.
        $pointRecords = ActivityPoint::with('event')
            ->where('student_id', $studentId)
            ->orderByDesc('awarded_at')
            ->limit(10)
            ->get();

        return view(
            'student.participation-progress.index',
            compact(
                'student',
                'totalEligibleEvents',
                'attendedEvents',
                'progressPercentage',
                'totalPoints',
                'attendanceRecords',
                'pointRecords'
            )
        );
    }
}
