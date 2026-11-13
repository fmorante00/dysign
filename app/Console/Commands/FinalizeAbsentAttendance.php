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
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Event-scoped Absent finalizer. Read-only unless --apply is supplied.
 * Never backfills all historical events or runs as a scheduler command.
 */
class FinalizeAbsentAttendance extends Command
{
    protected $signature = 'attendance:finalize-absent
        {--event= : Required event ID; no bulk event processing}
        {--apply : Explicitly create eligible Absent records (default is dry-run)}';

    protected $description = 'Preview or explicitly finalize absent expected students for ONE completed event.';

    private const TIMEZONE = 'Asia/Manila';

    public function handle(): int
    {
        $eventId = filter_var($this->option('event'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($eventId === false || $eventId === null) {
            $this->error('Specify a valid event ID, e.g. php artisan attendance:finalize-absent --event=33');
            return self::FAILURE;
        }

        $event = Event::query()->where('event_id', $eventId)->first();
        if (!$event) {
            $this->error("Event #{$eventId} does not exist.");
            return self::FAILURE;
        }

        $apply = (bool) $this->option('apply');
        $this->info(($apply ? 'APPLY' : 'DRY RUN ONLY') . " - Event #{$eventId}");

        if ((string) $event->status !== 'Completed') {
            $this->warn('Event must have status Completed before creating any Absent records. Current status: ' . $event->status);
            return self::SUCCESS;
        }

        try {
            $deadline = $this->checkoutDeadline($event);
        } catch (Throwable $exception) {
            $this->error('Invalid event schedule. Aborted without changes.');
            return self::FAILURE;
        }

        $this->line('Checkout deadline: ' . $deadline->format('Y-m-d h:i:s A') . ' (Asia/Manila)');
        $this->line('Current time: ' . Carbon::now(self::TIMEZONE)->format('Y-m-d h:i:s A') . ' (Asia/Manila)');

        if (!Carbon::now(self::TIMEZONE)->greaterThan($deadline)) {
            $this->warn('Checkout deadline has not passed. Absent finalization is not allowed.');
            return self::SUCCESS;
        }

        $counts = [
            'expected' => 0,
            'candidate' => 0,
            'created' => 0,
            'optional_not_registered' => 0,
            'inactive_student' => 0,
            'existing_attendance' => 0,
            'excuse_held' => 0,
            'exception_held' => 0,
            'discrepancy_held' => 0,
            'review_request_held' => 0,
            'other_participation' => 0,
            'changed_during_apply' => 0,
        ];

        try {
            EventEligibleStudent::query()
                ->where('event_id', $eventId)
                ->chunkById(200, function ($eligibilities) use ($eventId, $apply, &$counts) {
                    foreach ($eligibilities as $eligibility) {
                        $studentId = (int) $eligibility->student_id;
                        $participation = (string) $eligibility->participation_type;

                        // Count expected participants separately from held cases.
                        if ($participation === 'Required') {
                            $counts['expected']++;
                        } elseif ($participation === 'Optional') {
                            if (!$this->isRegisteredOptional($eventId, $studentId)) {
                                $counts['optional_not_registered']++;
                                continue;
                            }
                            $counts['expected']++;
                        } else {
                            $counts['other_participation']++;
                            continue;
                        }

                        $reason = $this->candidateCheck($eventId, $eligibility);
                        if ($reason !== 'candidate') {
                            $counts[$reason]++;
                            continue;
                        }

                        $counts['candidate']++;
                        if (!$apply) {
                            $this->line("Would mark Absent: event #{$eventId}, student #{$studentId} ({$participation}).");
                            continue;
                        }

                        // Transaction rechecks everything to minimize stale writes.
                        // Locking event + student serializes with cooperating finalizers.
                        // Unique(event_id,student_id) is the last duplicate safeguard.
                        try {
                            $result = DB::transaction(function () use ($eventId, $studentId) {
                                $lockedEvent = Event::query()
                                    ->where('event_id', $eventId)
                                    ->lockForUpdate()->first();

                                if (!$lockedEvent || (string) $lockedEvent->status !== 'Completed'
                                    || !Carbon::now(self::TIMEZONE)->greaterThan($this->checkoutDeadline($lockedEvent))) {
                                    return 'event_changed';
                                }

                                $student = Student::query()
                                    ->where('student_id', $studentId)
                                    ->lockForUpdate()->first();
                                $currentEligibility = EventEligibleStudent::query()
                                    ->where('event_id', $eventId)
                                    ->where('student_id', $studentId)
                                    ->lockForUpdate()->first();

                                if (!$currentEligibility || !$student) {
                                    return 'student_or_eligibility_changed';
                                }

                                if ($this->candidateCheck($eventId, $currentEligibility, $student) !== 'candidate') {
                                    return 'candidate_changed';
                                }

                                AttendanceRecord::query()->create([
                                    'event_id' => $eventId,
                                    'student_id' => $studentId,
                                    'rfid_identifier' => null,
                                    'time_in' => null,
                                    'time_out' => null,
                                    'status' => 'Absent',
                                    'scanned_by' => null,
                                ]);

                                return 'created';
                            }, 3);
                        } catch (QueryException $exception) {
                            // Another writer may have inserted the unique event/student row.
                            // Never ignore unrelated database failures.
                            if ((int) ($exception->errorInfo[1] ?? 0) !== 1062) {
                                throw $exception;
                            }
                            $result = 'duplicate_created_elsewhere';
                        }

                        if ($result === 'created') {
                            $counts['created']++;
                            $this->info("Created Absent: event #{$eventId}, student #{$studentId}.");
                        } else {
                            $counts['changed_during_apply']++;
                            $this->warn("Held student #{$studentId}: {$result}. No Absent record created by this command.");
                        }
                    }
                }, 'eligible_student_id');
        } catch (Throwable $exception) {
            $this->error('Finalizer stopped due to an error: ' . $exception->getMessage());
            if ($apply) {
                $this->warn('Some earlier students MAY have been finalized. Re-run the dry-run before retrying.');
            }
            return self::FAILURE;
        }

        $this->newLine();
        $this->table(['Check', 'Count'], [
            ['Expected Required + registered Optional', $counts['expected']],
            ['Eligible Absent candidates', $counts['candidate']],
            [$apply ? 'Absent records created' : 'Absent records created (dry run)', $counts['created']],
            ['Optional not registered (excluded)', $counts['optional_not_registered']],
            ['Inactive/missing student (held)', $counts['inactive_student']],
            ['Existing attendance (preserved)', $counts['existing_attendance']],
            ['Pending/approved excuse (held)', $counts['excuse_held']],
            ['Pending/approved scanner exception (held)', $counts['exception_held']],
            ['Pending/approved discrepancy (held)', $counts['discrepancy_held']],
            ['Pending/approved review request (held)', $counts['review_request_held']],
            ['Unknown participation type (held)', $counts['other_participation']],
            ['Changed during apply (held)', $counts['changed_during_apply']],
        ]);

        if ($apply) {
            $this->info('Apply finished. No scan timestamps or existing attendance rows were changed.');
        } else {
            $this->warn('DRY RUN ONLY. No database changes. --apply is deliberately required.');
        }
        $this->warn('Do not schedule this event-scoped command; use a separate reviewed scheduler design later.');

        return self::SUCCESS;
    }

    /** Returns candidate or a summary key explaining why this student is held. */
    private function candidateCheck(int $eventId, EventEligibleStudent $eligibility, ?Student $lockedStudent = null): string
    {
        $studentId = (int) $eligibility->student_id;
        $participation = (string) $eligibility->participation_type;

        if ($participation === 'Optional') {
            if (!$this->isRegisteredOptional($eventId, $studentId)) {
                return 'optional_not_registered';
            }
        } elseif ($participation !== 'Required') {
            return 'other_participation';
        }

        $student = $lockedStudent ?: Student::query()->where('student_id', $studentId)->first();
        if (!$student || $student->status !== 'Active') {
            return 'inactive_student';
        }

        if (AttendanceRecord::query()->where('event_id', $eventId)->where('student_id', $studentId)->exists()) {
            return 'existing_attendance';
        }

        if (ExcuseRequest::query()->where('event_id', $eventId)->where('student_id', $studentId)
            ->whereIn('status', ['Pending', 'Approved'])->exists()) {
            return 'excuse_held';
        }

        if (AttendanceException::query()->where('event_id', $eventId)->where('student_id', $studentId)
            ->whereIn('status', ['Pending', 'Approved'])->exists()) {
            return 'exception_held';
        }

        if (DB::table('attendance_discrepancies')->where('event_id', $eventId)
            ->where('student_id', $studentId)->whereIn('status', ['Pending', 'Approved'])->exists()) {
            return 'discrepancy_held';
        }

        if (DB::table('attendance_discrepancy_requests')->where('event_id', $eventId)
            ->where('student_id', $studentId)->whereIn('status', ['Pending', 'Approved'])->exists()) {
            return 'review_request_held';
        }

        return 'candidate';
    }

    private function isRegisteredOptional(int $eventId, int $studentId): bool
    {
        return DB::table('event_registrations')
            ->where('event_id', $eventId)
            ->where('student_id', $studentId)
            ->where('status', 'Registered')
            ->exists();
    }

    private function checkoutDeadline(Event $event): Carbon
    {
        $date = Carbon::parse($event->event_date, self::TIMEZONE)->format('Y-m-d');
        $start = Carbon::parse($event->start_time, self::TIMEZONE)->format('H:i:s');
        $end = Carbon::parse($event->end_time, self::TIMEZONE)->format('H:i:s');

        $startsAt = Carbon::parse("{$date} {$start}", self::TIMEZONE);
        $endsAt = Carbon::parse("{$date} {$end}", self::TIMEZONE);
        if ($endsAt->lessThan($startsAt)) {
            $endsAt->addDay();
        }
        return $endsAt->addHour();
    }
}
