<?php

namespace App\View\Components;

use App\Models\Event;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Student calendar preview. A registration is never submitted here.
 * The authoritative registration checks remain in EventRegistrationController.
 */
class StudentEventCalendar extends Component
{
    public function __construct(public Student $student)
    {
    }

    public function render(): View
    {
        $user = auth()->user();

        abort_unless(
            $user
                && $user->role?->role_name === 'Student Account'
                && (int) $user->student_id === (int) $this->student->student_id,
            403
        );

        $now = now('Asia/Manila');
        $firstMonth = $now->copy()->startOfMonth()->subMonths(6);
        $lastMonth = $now->copy()->startOfMonth()->addMonths(12)->endOfMonth();
        $studentId = (int) $this->student->student_id;

        $registrationsExist = Schema::hasTable('event_registrations');

        // Show only this student's eligible, registered, or attended events.
        // A withdrawn eligibility must not hide an existing registration.
        $events = Event::query()
            ->whereBetween('event_date', [$firstMonth->toDateString(), $lastMonth->toDateString()])
            ->where(function ($query) use ($studentId, $registrationsExist) {
                $query->whereHas('eligibleStudents', function ($eligible) use ($studentId) {
                    $eligible->where('student_id', $studentId);
                })->orWhereHas('attendanceRecords', function ($attendance) use ($studentId) {
                    $attendance->where('student_id', $studentId);
                });

                if ($registrationsExist) {
                    $query->orWhereIn('event_id', DB::table('event_registrations')
                        ->select('event_id')
                        ->where('student_id', $studentId));
                }
            })
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->limit(500)
            ->get(['event_id', 'event_name', 'event_date', 'start_time', 'end_time', 'location', 'status']);

        $eventIds = $events->pluck('event_id');

        $eligibleIds = DB::table('event_eligible_students')
            ->where('student_id', $studentId)
            ->whereIn('event_id', $eventIds)
            ->pluck('event_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $attendedIds = DB::table('attendance_records')
            ->where('student_id', $studentId)
            ->whereIn('event_id', $eventIds)
            ->distinct()
            ->pluck('event_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $registrationStatuses = $registrationsExist
            ? DB::table('event_registrations')
                ->where('student_id', $studentId)
                ->whereIn('event_id', $eventIds)
                ->pluck('status', 'event_id')
                ->all()
            : [];

        $studentActive = $this->student->status === 'Active';

        $calendarEvents = $events->map(function (Event $event) use (
            $eligibleIds, $attendedIds, $registrationStatuses, $studentActive
        ) {
            $eventId = (int) $event->event_id;
            $status = $event->calculateScheduledStatus();
            $eligible = in_array($eventId, $eligibleIds, true);
            $attended = in_array($eventId, $attendedIds, true);
            $registration = (string) ($registrationStatuses[$eventId] ?? '');
            $registered = $registration === 'Registered';
            $cancelledRegistration = $registration === 'Cancelled';

            // Matches the current server-side registration requirement: Upcoming + eligible.
            // A student who is already registered is shown as Registered, not invited to register again.
            $registrationOpen = $status === 'Upcoming' && $eligible && $studentActive;
            $canRegister = $registrationOpen && !$registered && !$attended;

            if ($status !== 'Upcoming') {
                $registrationStatus = 'Registration closed';
            } elseif (!$eligible) {
                $registrationStatus = 'Not eligible';
            } elseif (!$studentActive) {
                $registrationStatus = 'Account inactive';
            } else {
                $registrationStatus = 'Registration open';
            }

            return [
                'id' => $eventId,
                'title' => (string) $event->event_name,
                'date' => $event->event_date->format('Y-m-d'),
                'sortTime' => $event->start_time
                    ? \Illuminate\Support\Carbon::parse($event->start_time)->format('H:i:s')
                    : '99:99:99',
                'time' => $event->start_time
                    ? \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A')
                    : 'To be announced',
                'endTime' => $event->end_time
                    ? \Illuminate\Support\Carbon::parse($event->end_time)->format('g:i A')
                    : null,
                'location' => (string) ($event->location ?: 'Venue to be announced'),
                'eventStatus' => $status,
                'registrationStatus' => $registrationStatus,
                'registrationOpen' => $registrationOpen,
                'canRegister' => $canRegister,
                'registered' => $registered,
                'cancelledRegistration' => $cancelledRegistration,
                'eligible' => $eligible,
                'attended' => $attended,
            ];
        })->values()->all();

        return view('components.student-event-calendar', [
            'calendarEvents' => $calendarEvents,
            'calendarToday' => $now->toDateString(),
            'calendarFirstMonth' => $firstMonth->format('Y-m'),
            'calendarLastMonth' => $lastMonth->format('Y-m'),
        ]);
    }
}
