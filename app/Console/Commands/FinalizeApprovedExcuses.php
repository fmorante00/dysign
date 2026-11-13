<?php

namespace App\Console\Commands;

use App\Models\AttendanceRecord;
use App\Models\EventEligibleStudent;
use App\Models\ExcuseRequest;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class FinalizeApprovedExcuses extends Command
{
    protected $signature = 'attendance:finalize-excused {--dry-run : Preview eligible approved excuses without creating attendance records}';

    protected $description = 'Create Excused attendance for approved excuse requests only after the event checkout deadline.';

    private const EVENT_TIMEZONE = 'Asia/Manila';

    public function handle(): int
    {
        $now = Carbon::now(self::EVENT_TIMEZONE);
        $dryRun = (bool) $this->option('dry-run');
        $eligible = 0;
        $created = 0;
        $skipped = 0;

        $this->info(($dryRun ? 'DRY RUN' : 'FINALIZATION') . ' - ' . $now->format('Y-m-d h:i:s A') . ' (Asia/Manila)');

        ExcuseRequest::query()
            ->with('event')
            ->where('status', 'Approved')
            ->chunkById(200, function ($excuses) use ($now, $dryRun, &$eligible, &$created, &$skipped) {
                foreach ($excuses as $excuse) {
                    $event = $excuse->event;

                    if (!$event || $this->isCancelled($event->status)) {
                        $skipped++;
                        continue;
                    }

                    try {
                        $deadline = $this->checkoutDeadline($event);
                    } catch (Throwable $exception) {
                        $skipped++;
                        $this->warn("Skipped excuse #{$excuse->excuse_request_id}: invalid event schedule.");
                        continue;
                    }

                    // A checkout is still accepted at the exact deadline.
                    if (!$now->greaterThan($deadline)) {
                        continue;
                    }

                    // Only expected participants should receive an attendance record.
                    if (!$this->isExpectedParticipant((int) $event->event_id, (int) $excuse->student_id)) {
                        $skipped++;
                        continue;
                    }

                    // Never replace RFID attendance, Incomplete, Absent, or a manual exception record.
                    if (AttendanceRecord::query()
                        ->where('event_id', $event->event_id)
                        ->where('student_id', $excuse->student_id)
                        ->exists()) {
                        continue;
                    }

                    $eligible++;

                    if ($dryRun) {
                        $this->line("Would create Excused for excuse #{$excuse->excuse_request_id}, event #{$event->event_id}, student #{$excuse->student_id}.");
                        continue;
                    }

                    // Serializes with RFID/manual scan paths that also lock the student row.
                    // The database's unique(event_id, student_id) constraint adds protection.
                    $didCreate = DB::transaction(function () use ($excuse, $now) {
                        $lockedExcuse = ExcuseRequest::query()
                            ->whereKey($excuse->getKey())
                            ->lockForUpdate()
                            ->first();

                        if (!$lockedExcuse || $lockedExcuse->status !== 'Approved') {
                            return false;
                        }

                        $lockedEvent = DB::table('events')
                            ->where('event_id', $lockedExcuse->event_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$lockedEvent || $this->isCancelled($lockedEvent->status)) {
                            return false;
                        }

                        try {
                            if (!$now->greaterThan($this->checkoutDeadline($lockedEvent))) {
                                return false;
                            }
                        } catch (Throwable $exception) {
                            return false;
                        }

                        $student = Student::query()
                            ->where('student_id', $lockedExcuse->student_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$student || !$this->isExpectedParticipant((int) $lockedEvent->event_id, (int) $student->student_id)) {
                            return false;
                        }

                        if (AttendanceRecord::query()
                            ->where('event_id', $lockedEvent->event_id)
                            ->where('student_id', $student->student_id)
                            ->exists()) {
                            return false;
                        }

                        AttendanceRecord::query()->create([
                            'event_id' => $lockedEvent->event_id,
                            'student_id' => $student->student_id,
                            'rfid_identifier' => null,
                            'time_in' => null,
                            'time_out' => null,
                            'status' => 'Excused',
                            'scanned_by' => null,
                        ]);

                        return true;
                    });

                    if ($didCreate) {
                        $created++;
                    }
                }
            }, 'excuse_request_id');

        $this->info($dryRun
            ? "Dry run finished. Eligible Excused records: {$eligible}. Skipped invalid/cancelled/unexpected: {$skipped}. No database changes."
            : "Finalization finished. Excused records created: {$created}. Skipped invalid/cancelled/unexpected: {$skipped}.");

        return self::SUCCESS;
    }

    private function isCancelled(?string $status): bool
    {
        return in_array(strtolower((string) $status), ['cancelled', 'canceled'], true);
    }

    private function checkoutDeadline(object $event): Carbon
    {
        $date = Carbon::parse($event->event_date, self::EVENT_TIMEZONE)->format('Y-m-d');
        $startTime = Carbon::parse($event->start_time, self::EVENT_TIMEZONE)->format('H:i:s');
        $endTime = Carbon::parse($event->end_time, self::EVENT_TIMEZONE)->format('H:i:s');
        $startsAt = Carbon::parse("{$date} {$startTime}", self::EVENT_TIMEZONE);
        $endsAt = Carbon::parse("{$date} {$endTime}", self::EVENT_TIMEZONE);

        if ($endsAt->lessThan($startsAt)) {
            $endsAt->addDay();
        }

        return $endsAt->addHour();
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

        if (strcasecmp((string) $eligibility->participation_type, 'Optional') !== 0) {
            return strcasecmp((string) $eligibility->participation_type, 'Required') === 0;
        }

        return DB::table('event_registrations')
            ->where('event_id', $eventId)
            ->where('student_id', $studentId)
            ->where('status', 'Registered')
            ->exists();
    }
}
