<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDiscrepancyRequest;
use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentAttendanceDiscrepancyController extends Controller
{
    public function index()
    {
        $student = $this->authorizedStudent();

        // Display completed/current dates; never offer future scheduled events.
        $events = Event::query()
            ->whereDate('event_date', '<=', today())
            ->orderByDesc('event_date')
            ->limit(100)
            ->get(['event_id', 'event_name', 'event_date', 'status']);

        $attendanceByEvent = AttendanceRecord::query()
            ->where('student_id', $student->student_id)
            ->whereIn('event_id', $events->pluck('event_id'))
            ->orderByDesc('time_in')
            ->get()
            ->keyBy('event_id');

        $requests = AttendanceDiscrepancyRequest::query()
            ->with(['event', 'reviewer'])
            ->where('student_id', $student->student_id)
            ->latest('discrepancy_request_id')
            ->paginate(10);

        return view('attendance-discrepancies.student-index', compact(
            'student', 'events', 'attendanceByEvent', 'requests'
        ));
    }

    public function store(Request $request)
    {
        $student = $this->authorizedStudent();

        $data = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,event_id'],
            'request_type' => ['required', Rule::in(['Missing Attendance', 'Incorrect Attendance'])],
            'description' => ['required', 'string', 'min:15', 'max:2000'],
            'expected_time_in' => ['nullable', 'date', 'before_or_equal:now'],
        ]);

        DB::transaction(function () use ($student, $data, $request): void {
            // Lock the event to serialize submissions for the same event.
            $event = Event::query()->where('event_id', $data['event_id'])
                ->lockForUpdate()->firstOrFail();

            if (\Illuminate\Support\Carbon::parse($event->event_date)->isFuture()) {
                throw ValidationException::withMessages([
                    'event_id' => 'A request cannot be submitted before the event date.',
                ]);
            }

            $pendingKey = $student->student_id . ':' . $event->event_id;
            if (AttendanceDiscrepancyRequest::where('pending_key', $pendingKey)->exists()) {
                throw ValidationException::withMessages([
                    'event_id' => 'You already have a pending discrepancy request for this event.',
                ]);
            }

            $attendance = AttendanceRecord::query()
                ->where('event_id', $event->event_id)
                ->where('student_id', $student->student_id)
                ->orderBy('attendance_id')
                ->first();

            if ($data['request_type'] === 'Missing Attendance' && $attendance) {
                throw ValidationException::withMessages([
                    'request_type' => 'Attendance already exists for this event. Choose Incorrect Attendance instead.',
                ]);
            }

            if ($data['request_type'] === 'Incorrect Attendance' && !$attendance) {
                throw ValidationException::withMessages([
                    'request_type' => 'No attendance record exists for this event. Choose Missing Attendance instead.',
                ]);
            }

            AttendanceDiscrepancyRequest::create([
                'student_id' => $student->student_id,
                'event_id' => $event->event_id,
                'attendance_id' => $attendance?->attendance_id,
                'requested_by' => $request->user()->user_id,
                'request_type' => $data['request_type'],
                'description' => $data['description'],
                'expected_time_in' => $data['expected_time_in'] ?? null,
                'original_time_in' => $attendance?->time_in,
                'original_attendance_status' => $attendance?->status,
                'status' => 'Pending',
                'pending_key' => $pendingKey,
            ]);
        }, 3);

        return redirect()->route('attendance-discrepancies.student.index')
            ->with('success', 'Your request was submitted for Attendance Personnel review.');
    }

    private function authorizedStudent(): Student
    {
        $user = auth()->user();
        abort_unless($user && $user->role?->role_name === 'Student Account', 403);

        $student = $user->student;
        abort_unless(
            $student && $student->status === 'Active',
            403,
            'No active student record is linked to this account.'
        );

        return $student;
    }
}
