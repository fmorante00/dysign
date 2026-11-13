<?php

namespace App\Console\Commands;

use App\Models\AttendanceException;
use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\EventEligibleStudent;
use App\Models\ExcuseRequest;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Read-only, per-event preview of potentially absent expected participants.
 *
 * Never writes attendance. Both discrepancy workflows must be considered
 * before an actual Absent finalizer is designed or scheduled.
 */
class PreviewAbsentAttendance extends Command
{
    protected $signature = 'attendance:preview-absent
        {--event= : Required event ID (prevents historical bulk backfills)}';

    protected $description = 'Preview expected participants who could be Absent after the checkout deadline; never writes data.';

    private const EVENT_TIMEZONE = 'Asia/Manila';

    public function handle(): int
    {
        $rawEventId = $this->option('event');
        $eventId = filter_var($rawEventId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($eventId === false || $eventId === null) {
            $this->error('Specify a valid event ID, e.g. php artisan attendance:preview-absent --event=33');
            return self::FAILURE;
        }

        $event = Event::query()->where('event_id', $eventId)->first();
        if (!$event) {
            $this->error("Event #{$eventId} does not exist.");
            return self::FAILURE;
        }

        if ($this->isCancelled($event->status)) {
            $this->warn("Event #{$eventId} is cancelled; no Absent records should be created.");
            return self::SUCCESS;
        }

        try {
            $deadline = $this->checkoutDeadline($event);
        } catch (Throwable $exception) {
            $this->error('Invalid event schedule; preview aborted.');
            return self::FAILURE;
        }

        $now = Carbon::now(self::EVENT_TIMEZONE);
        $this->line("DRY RUN ONLY - Event #{$eventId}");
        $this->line('Checkout deadline: ' . $deadline->format('Y-m-d h:i:s A') . ' (Asia/Manila)');
        $this->line('Current time: ' . $now->format('Y-m-d h:i:s A') . ' (Asia/Manila)');

        if (!$now->greaterThan($deadline)) {
            $this->warn('Checkout deadline has not passed. No Absent finalization is allowed yet.');
            return self::SUCCESS;
        }

        $stats = [
            'expected' => 0,
            'eligible_absent' => 0,
            'not_registered_optional' => 0,
            'inactive' => 0,
            'existing_attendance' => 0,
            'pending_or_approved_excuse' => 0,
            'pending_scanner_exception' => 0,
            'pending_or_approved_discrepancy' => 0,
            'pending_or_approved_review_request' => 0,
            'other_eligibility' => 0,
        ];

        EventEligibleStudent::query()
            ->where('event_id', $eventId)
            ->orderBy('eligible_student_id')
            ->chunkById(200, function ($eligibilities) use ($eventId, &$stats) {
                foreach ($eligibilities as $eligibility) {
                    $studentId = (int) $eligibility->student_id;
                    $participation = (string) $eligibility->participation_type;

                    // Required is expected automatically. Optional requires active registration.
                    if ($participation === 'Optional') {
                        $registered = DB::table('event_registrations')
                            ->where('event_id', $eventId)
                            ->where('student_id', $studentId)
                            ->where('status', 'Registered')
                            ->exists();

                        if (!$registered) {
                            $stats['not_registered_optional']++;
                            continue;
                        }
                    } elseif ($participation !== 'Required') {
                        $stats['other_eligibility']++;
                        continue;
                    }

                    $stats['expected']++;

                    // A currently inactive student requires separate school policy review.
                    $student = Student::query()->where('student_id', $studentId)->first();
                    if (!$student || $student->status !== 'Active') {
                        $stats['inactive']++;
                        continue;
                    }

                    // Existing attendance (including Incomplete, Excused or Absent) is preserved.
                    if (AttendanceRecord::query()
                        ->where('event_id', $eventId)
                        ->where('student_id', $studentId)
                        ->exists()) {
                        $stats['existing_attendance']++;
                        continue;
                    }

                    // Pending excuses are unresolved; approved excuses have their own finalizer.
                    if (ExcuseRequest::query()
                        ->where('event_id', $eventId)
                        ->where('student_id', $studentId)
                        ->whereIn('status', ['Pending', 'Approved'])
                        ->exists()) {
                        $stats['pending_or_approved_excuse']++;
                        continue;
                    }

                    // An unresolved scanner failure should not be treated as a confirmed absence.
                    if (AttendanceException::query()
                        ->where('event_id', $eventId)
                        ->where('student_id', $studentId)
                        ->where('status', 'Pending')
                        ->exists()) {
                        $stats['pending_scanner_exception']++;
                        continue;
                    }

                    // Workflow A: student/attendance-discrepancies.
                    // Approved correction requests are held as a precaution until the
                    // full official-status and correction workflows are reconciled.
                    if (DB::table('attendance_discrepancies')
                        ->where('event_id', $eventId)
                        ->where('student_id', $studentId)
                        ->whereIn('status', ['Pending', 'Approved'])
                        ->exists()) {
                        $stats['pending_or_approved_discrepancy']++;
                        continue;
                    }

                    // Workflow B: student/attendance-discrepancy-requests.
                    // This workflow records an administrative decision but does not
                    // automatically create or update attendance_records.
                    if (DB::table('attendance_discrepancy_requests')
                        ->where('event_id', $eventId)
                        ->where('student_id', $studentId)
                        ->whereIn('status', ['Pending', 'Approved'])
                        ->exists()) {
                        $stats['pending_or_approved_review_request']++;
                        continue;
                    }

                    $stats['eligible_absent']++;
                    $this->line("Would mark Absent: event #{$eventId}, student #{$studentId} ({$participation}).");
                }
            }, 'eligible_student_id');

        $this->newLine();
        $this->info('Preview finished; no database records were created or modified.');
        $this->table(['Check', 'Count'], [
            ['Expected Required + registered Optional', $stats['expected']],
            ['Would be Absent', $stats['eligible_absent']],
            ['Optional not registered (excluded)', $stats['not_registered_optional']],
            ['Inactive/missing student (held)', $stats['inactive']],
            ['Has existing attendance (preserved)', $stats['existing_attendance']],
            ['Pending/approved excuse (held)', $stats['pending_or_approved_excuse']],
            ['Pending scanner exception (held)', $stats['pending_scanner_exception']],
            ['Pending/approved discrepancy (held)', $stats['pending_or_approved_discrepancy']],
            ['Pending/approved review request (held)', $stats['pending_or_approved_review_request']],
            ['Other eligibility type (held)', $stats['other_eligibility']],
        ]);
        $this->warn('PREVIEW ONLY. Absent writes/scheduling remain disabled until discrepancy corrections and late-approved excuses are coordinated.');

        return self::SUCCESS;
    }

    private function isCancelled(?string $status): bool
    {
        return in_array(strtolower((string) $status), ['cancelled', 'canceled'], true);
    }

    private function checkoutDeadline(Event $event): Carbon
    {
        $date = Carbon::parse($event->event_date, self::EVENT_TIMEZONE)->format('Y-m-d');
        $start = Carbon::parse($event->start_time, self::EVENT_TIMEZONE)->format('H:i:s');
        $end = Carbon::parse($event->end_time, self::EVENT_TIMEZONE)->format('H:i:s');

        $startsAt = Carbon::parse("{$date} {$start}", self::EVENT_TIMEZONE);
        $endsAt = Carbon::parse("{$date} {$end}", self::EVENT_TIMEZONE);

        if ($endsAt->lessThan($startsAt)) {
            $endsAt->addDay();
        }

        return $endsAt->addHour();
    }
}
