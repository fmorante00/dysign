<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Administrator-only, read-only attendance monitoring.
 * Route middleware enforces the Administrator role; this controller never writes attendance.
 */
class AttendanceMonitoringController extends Controller
{
    public function index(Request $request)
    {
        // Match the existing monitoring policy: all events except cancelled ones.
        $events = Event::query()
            ->where('status', '!=', 'Cancelled')
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->get();

        $selectedEvent = null;
        $attendanceRecords = collect();
        $totals = $this->emptyTotals();

        if ($request->filled('event_id')) {
            $selectedEvent = Event::query()
                ->where('status', '!=', 'Cancelled')
                ->where('event_id', $request->integer('event_id'))
                ->firstOrFail();

            $totals = $this->attendanceTotals($selectedEvent->event_id);
            $attendanceRecords = $this->attendanceQuery($selectedEvent->event_id)
                ->limit(50)
                ->get();
        }

        // Calculated from the schedule, without modifying database records.
        $eventStatus = $selectedEvent?->calculateScheduledStatus();
        $activeEvents = $events->filter(fn (Event $event) =>
            $event->calculateScheduledStatus() === 'Ongoing'
        )->values();

        return view('attendance.monitor', [
            'events' => $events,
            'activeEvents' => $activeEvents,
            'selectedEvent' => $selectedEvent,
            'eventStatus' => $eventStatus,
            'attendanceRecords' => $attendanceRecords,
            'latestAttendance' => $attendanceRecords->first(),
            'attendanceCount' => $totals['attendance_count'],
            'presentCount' => $totals['present_count'],
            'lateCount' => $totals['late_count'],
            'checkedInCount' => $totals['checked_in_count'],
            'checkedOutCount' => $totals['checked_out_count'],
        ]);
    }

    /**
     * Polling endpoint used by the Administrator monitoring page every five seconds.
     * Returns data only; no scanner actions or attendance updates occur here.
     */
    public function feed($event)
    {
        $event = Event::query()
            ->where('status', '!=', 'Cancelled')
            ->where('event_id', $event)
            ->firstOrFail();

        $totals = $this->attendanceTotals($event->event_id);

        $records = $this->attendanceQuery($event->event_id)
            ->limit(50)
            ->get()
            ->map(function ($record) {
                $name = trim(implode(' ', array_filter([
                    $record->first_name,
                    $record->middle_name,
                    $record->last_name,
                ])));

                // A checkout modifies an existing attendance row, so order and
                // highlight by the most recent check-in OR check-out timestamp.
                $isCheckout = !empty($record->time_out);
                $activityAt = $record->time_out ?: $record->time_in;

                return [
                    'attendance_id' => $record->attendance_id,
                    'student_number' => $record->student_number,
                    'name' => $name,
                    'initial' => strtoupper(substr((string) $record->first_name, 0, 1)),
                    'program_code' => $record->program_code,
                    'year_level' => $record->year_level,
                    'photo_url' => $record->photo_path
                        ? asset('student_photos/' . basename($record->photo_path))
                        : null,
                    'status' => $record->status,
                    'time_in' => $record->time_in
                        ? Carbon::parse($record->time_in)->format('h:i A')
                        : '—',
                    'time_out' => $record->time_out
                        ? Carbon::parse($record->time_out)->format('h:i A')
                        : '—',
                    'activity_type' => $isCheckout ? 'Check-out' : 'Check-in',
                    'activity_time' => $activityAt
                        ? Carbon::parse($activityAt)->format('h:i A')
                        : '—',
                    'activity_at' => $activityAt
                        ? Carbon::parse($activityAt)->toIso8601String()
                        : null,
                    'scanned_by' => $record->scanned_by_name ?: 'Unknown',
                ];
            })
            ->values();

        return response()->json(array_merge($totals, [
            'event_status' => $event->calculateScheduledStatus(),
            'latest' => $records->first(),
            'records' => $records,
            'server_time' => Carbon::now('Asia/Manila')->format('h:i:s A'),
        ]))->header('Cache-Control', 'no-store, private');
    }

    private function emptyTotals(): array
    {
        return [
            'attendance_count' => 0,
            'present_count' => 0,
            'late_count' => 0,
            'checked_in_count' => 0,
            'checked_out_count' => 0,
        ];
    }

    private function attendanceTotals(int $eventId): array
    {
        // Counts are across the FULL event, not only the 50 most recent rows.
        $summary = DB::table('attendance_records')
            ->where('event_id', $eventId)
            ->selectRaw('COUNT(*) as attendance_count')
            ->selectRaw("SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_count")
            ->selectRaw("SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late_count")
            ->selectRaw("SUM(CASE WHEN status = 'Checked In' THEN 1 ELSE 0 END) as checked_in_count")
            ->selectRaw('SUM(CASE WHEN time_out IS NOT NULL THEN 1 ELSE 0 END) as checked_out_count')
            ->first();

        return [
            'attendance_count' => (int) ($summary->attendance_count ?? 0),
            'present_count' => (int) ($summary->present_count ?? 0),
            'late_count' => (int) ($summary->late_count ?? 0),
            'checked_in_count' => (int) ($summary->checked_in_count ?? 0),
            'checked_out_count' => (int) ($summary->checked_out_count ?? 0),
        ];
    }

    private function attendanceQuery(int $eventId)
    {
        return DB::table('attendance_records as ar')
            ->join('students as s', 's.student_id', '=', 'ar.student_id')
            ->leftJoin('users as u', 'u.user_id', '=', 'ar.scanned_by')
            ->where('ar.event_id', $eventId)
            ->select([
                'ar.attendance_id', 'ar.event_id', 'ar.time_in', 'ar.time_out',
                'ar.status', 'ar.scanned_by', 's.student_id', 's.student_number',
                's.first_name', 's.middle_name', 's.last_name', 's.program_code',
                's.year_level', 's.photo_path', 'u.name as scanned_by_name',
            ])
            ->orderByRaw('COALESCE(ar.time_out, ar.time_in) DESC')
            ->orderByDesc('ar.attendance_id');
    }
}
