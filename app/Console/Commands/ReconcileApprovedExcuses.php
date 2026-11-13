<?php

namespace App\Console\Commands;

use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\EventEligibleStudent;
use App\Models\ExcuseRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Convert a scan-free Absent record to Excused when its excuse is approved.
 *
 * The default mode is read-only. Each actual update requires both an event
 * scope and --apply. Existing scan evidence is never changed.
 */
class ReconcileApprovedExcuses extends Command
{
    protected $signature = 'attendance:reconcile-excused
        {--event= : Required event ID; prevents historical bulk updates}
        {--apply : Apply eligible Absent to Excused corrections (default: preview only)}';

    protected $description = 'Preview or safely apply Absent-to-Excused corrections for approved excuse requests in one event.';

    private const EVENT_TIMEZONE = 'Asia/Manila';

    public function handle(): int
    {
        $eventId = filter_var($this->option('event'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($eventId === false || $eventId === null) {
            $this->error('Provide a positive event ID, e.g. attendance:reconcile-excused --event=27');
            return self::FAILURE;
        }

        $event = Event::query()->where('event_id', $eventId)->first();
        if (!$event) {
            $this->error("Event #{$eventId} does not exist.");
            return self::FAILURE;
        }

        if ($this->isCancelled($event->status)) {
            $this->warn("Event #{$eventId} is cancelled. No corrections allowed.");
            return self::SUCCESS;
        }

        try {
            $deadline = $this->checkoutDeadline($event);
        } catch (Throwable $exception) {
            $this->error("Event #{$eventId} has an invalid schedule. No corrections allowed.");
            return self::FAILURE;
        }

        $now = Carbon::now(self::EVENT_TIMEZONE);
        $apply = (bool) $this->option('apply');
        $this->info(($apply ? 'APPLY' : 'DRY RUN ONLY') . " - Event #{$eventId}");
        $this->line('Checkout deadline: ' . $deadline->format('Y-m-d h:i:s A') . ' (Asia/Manila)');

        if (!$now->greaterThan($deadline)) {
            $this->warn('Event checkout deadline has not passed. No corrections allowed.');
            return self::SUCCESS;
        }

        $counts = [
            'approved' => 0,
            'eligible' => 0,
            'converted' => 0,
            'already_excused' => 0,
            'no_attendance' => 0,
            'protected' => 0,
            'not_expected' => 0,
            'changed_during_apply' => 0,
        ];

        ExcuseRequest::query()
            ->where('event_id', $eventId)
            ->where('status', 'Approved')
            ->orderBy('excuse_request_id')
            ->chunkById(200, function ($excuses) use ($eventId, $apply, &$counts) {
                foreach ($excuses as $excuse) {
                    $counts['approved']++;
                    $studentId = (int) $excuse->student_id;
                    $requestId = (int) $excuse->excuse_request_id;

                    if (!$this->isExpectedParticipant($eventId, $studentId)) {
                        $counts['not_expected']++;
                        $this->warn("Held excuse #{$requestId}: student #{$studentId} is not an expected participant.");
                        continue;
                    }

                    $attendance = AttendanceRecord::query()
                        ->where('event_id', $eventId)
                        ->where('student_id', $studentId)
                        ->first();

                    if (!$attendance) {
                        // Creating new Excused attendance is handled by
                        // attendance:finalize-excused, not by this command.
                        $counts['no_attendance']++;
                        continue;
                    }

                    if ($attendance->status === 'Excused') {
                        $counts['already_excused']++;
                        continue;
                    }

                    if (!$this->isSafeAbsent($attendance)) {
                        // Protect all scan/manual evidence and non-Absent statuses.
                        $counts['protected']++;
                        continue;
                    }

                    $counts['eligible']++;
                    if (!$apply) {
                        $this->line("Would convert attendance #{$attendance->attendance_id}: event #{$eventId}, student #{$studentId}, Absent -> Excused (approved excuse #{$requestId}).");
                        continue;
                    }

                    // Recheck the authorization facts and row under locks.
                    // Another command or reviewer may have changed them
                    // since the initial read.
                    $didConvert = DB::transaction(function () use ($requestId, $eventId, $studentId) {
                        $lockedExcuse = ExcuseRequest::query()
                            ->where('excuse_request_id', $requestId)
                            ->lockForUpdate()
                            ->first();
                        if (!$lockedExcuse || $lockedExcuse->status !== 'Approved'
                            || (int) $lockedExcuse->event_id !== $eventId
                            || (int) $lockedExcuse->student_id !== $studentId) {
                            return false;
                        }

                        $lockedEvent = DB::table('events')
                            ->where('event_id', $eventId)
                            ->lockForUpdate()
                            ->first();
                        if (!$lockedEvent || $this->isCancelled($lockedEvent->status)) {
                            return false;
                        }
                        try {
                            if (!Carbon::now(self::EVENT_TIMEZONE)
                                ->greaterThan($this->checkoutDeadline($lockedEvent))) {
                                return false;
                            }
                        } catch (Throwable $exception) {
                            return false;
                        }

                        // Serialize with scanner and finalizer paths that
                        // lock the student before creating attendance.
                        $student = DB::table('students')
                            ->where('student_id', $studentId)
                            ->lockForUpdate()
                            ->first();
                        if (!$student || !$this->isExpectedParticipant($eventId, $studentId)) {
                            return false;
                        }

                        $lockedAttendance = AttendanceRecord::query()
                            ->where('event_id', $eventId)
                            ->where('student_id', $studentId)
                            ->lockForUpdate()
                            ->first();
                        if (!$lockedAttendance || !$this->isSafeAbsent($lockedAttendance)) {
                            return false;
                        }

                        // Status-only change. Leave scan fields and source
                        // evidence untouched, and keep timestamps for audit.
                        $lockedAttendance->status = 'Excused';
                        $lockedAttendance->save();
                        return true;
                    }, 3);

                    if ($didConvert) {
                        $counts['converted']++;
                        $this->info("Converted attendance #{$attendance->attendance_id}: Absent -> Excused.");
                    } else {
                        $counts['changed_during_apply']++;
                        $this->warn("Held excuse #{$requestId}: data changed during recheck; no update made.");
                    }
                }
            }, 'excuse_request_id');

        $this->newLine();
        $this->table(['Check', 'Count'], [
            ['Approved excuse requests for this event', $counts['approved']],
            ['Eligible Absent -> Excused', $counts['eligible']],
            [$apply ? 'Converted in database' : 'Converted in database (dry run)', $counts['converted']],
            ['Already Excused (preserved)', $counts['already_excused']],
            ['No attendance (existing finalizer handles)', $counts['no_attendance']],
            ['Protected other statuses / scan evidence', $counts['protected']],
            ['Not an expected participant', $counts['not_expected']],
            ['Held due to concurrent changes', $counts['changed_during_apply']],
        ]);

        if (!$apply) {
            $this->warn('PREVIEW ONLY: No database changes. To apply later, explicitly add --apply.');
        } else {
            $this->info('Apply finished. Only eligible scan-free Absent rows were changed.');
        }

        return self::SUCCESS;
    }

    private function isSafeAbsent(AttendanceRecord $attendance): bool
    {
        return $attendance->status === 'Absent'
            && $attendance->time_in === null
            && $attendance->time_out === null
            && $attendance->rfid_identifier === null
            && $attendance->scanned_by === null;
    }

    private function isExpectedParticipant(int $eventId, int $studentId): bool
    {
        $eligibility = EventEligibleStudent::query()
            ->where('event_id', $eventId)
            ->where('student_id', $studentId)
            ->first();

        if (!$eligibility) {
            return false;
        }

        if ((string) $eligibility->participation_type === 'Required') {
            return true;
        }

        if ((string) $eligibility->participation_type !== 'Optional') {
            return false;
        }

        return DB::table('event_registrations')
            ->where('event_id', $eventId)
            ->where('student_id', $studentId)
            ->where('status', 'Registered')
            ->exists();
    }

    private function isCancelled(?string $status): bool
    {
        return in_array(strtolower((string) $status), ['cancelled', 'canceled'], true);
    }

    private function checkoutDeadline(object $event): Carbon
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
