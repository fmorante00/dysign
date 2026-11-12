<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDiscrepancyRequest;
use App\Models\AttendanceRecord;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PersonnelAttendanceDiscrepancyController extends Controller
{
    public function index(Request $request)
    {
        $personnelId = $this->personnelId();
        $eventIds = Event::query()
            ->whereHas('assignments', fn ($q) => $q->where('personnel_id', $personnelId))
            ->select('event_id');

        $status = $request->query('status', 'All');
        if (!in_array($status, ['All', 'Pending', 'Approved', 'Rejected'], true)) {
            $status = 'All';
        }

        $base = AttendanceDiscrepancyRequest::query()
            ->whereIn('event_id', $eventIds);

        $counts = [
            'all' => (clone $base)->count(),
            'pending' => (clone $base)->where('status', 'Pending')->count(),
            'resolved' => (clone $base)->whereIn('status', ['Approved', 'Rejected'])->count(),
        ];

        $requests = $base
            ->when($status !== 'All', fn ($q) => $q->where('status', $status))
            ->with(['student', 'event', 'attendanceRecord', 'reviewer'])
            ->latest('discrepancy_request_id')
            ->paginate(12)
            ->withQueryString();

        return view('staff.attendance-discrepancies.index', compact('requests', 'counts', 'status'));
    }

    public function review(Request $request, int $discrepancyRequest)
    {
        $personnelId = $this->personnelId();

        $data = $request->validate([
            'decision' => ['required', Rule::in(['Approved', 'Rejected'])],
            'resolution_notes' => ['required', 'string', 'min:10', 'max:2000'],
            'verified_time_in' => ['nullable', 'required_if:decision,Approved', 'date', 'before_or_equal:now'],
        ]);

        DB::transaction(function () use ($discrepancyRequest, $personnelId, $request, $data): void {
            $entry = AttendanceDiscrepancyRequest::query()
                ->where('discrepancy_request_id', $discrepancyRequest)
                ->lockForUpdate()
                ->firstOrFail();

            // Prevent ID guessing from exposing or resolving another staff member's event.
            $authorized = Event::query()
                ->where('event_id', $entry->event_id)
                ->whereHas('assignments', fn ($q) => $q->where('personnel_id', $personnelId))
                ->exists();
            abort_unless($authorized, 403, 'You are not assigned to this event.');

            if ($entry->status !== 'Pending') {
                throw ValidationException::withMessages([
                    'decision' => 'This request has already been resolved.',
                ]);
            }

            // Re-check live attendance as the data may have changed since submission.
            $current = AttendanceRecord::query()
                ->where('event_id', $entry->event_id)
                ->where('student_id', $entry->student_id)
                ->first();

            if ($data['decision'] === 'Approved') {
                if ($entry->request_type === 'Missing Attendance' && $current) {
                    throw ValidationException::withMessages([
                        'decision' => 'Attendance now exists. Review the latest record before approving.',
                    ]);
                }
                if ($entry->request_type === 'Incorrect Attendance' && !$current) {
                    throw ValidationException::withMessages([
                        'decision' => 'The original attendance record is no longer available.',
                    ]);
                }

                // Staff must verify a real event time, never automatically invent one.
                $event = Event::query()->where('event_id', $entry->event_id)->firstOrFail();
                $verifiedDate = \Illuminate\Support\Carbon::parse($data['verified_time_in'])->toDateString();
                $eventDate = \Illuminate\Support\Carbon::parse($event->event_date)->toDateString();
                if ($verifiedDate !== $eventDate) {
                    throw ValidationException::withMessages([
                        'verified_time_in' => 'Verified attendance must be on the event date.',
                    ]);
                }
            }

            $entry->update([
                'status' => $data['decision'],
                'pending_key' => null,
                'resolution_notes' => $data['resolution_notes'],
                'verified_time_in' => $data['decision'] === 'Approved' ? $data['verified_time_in'] : null,
                'reviewed_by' => $request->user()->user_id,
                'reviewed_at' => now(),
            ]);

            // IMPORTANT: Do not change attendance_records here. They are raw RFID
            // scan evidence. Approval is a verified administrative decision, not
            // a fabricated RFID scan. Reporting integration is a separate step.
        }, 3);

        return redirect()->route('attendance-discrepancies.staff.index')
            ->with('success', 'Discrepancy request reviewed and recorded.');
    }

    private function personnelId(): int
    {
        $user = auth()->user();
        abort_unless($user && $user->role?->role_name === 'Attendance Personnel', 403);
        abort_unless($user->personnel, 403, 'No personnel profile is linked to this account.');

        return (int) $user->personnel->personnel_id;
    }
}
