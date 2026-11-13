<?php

namespace App\Console\Commands;

use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class FinalizeIncompleteAttendance extends Command
{
    protected $signature = 'attendance:finalize-incomplete {--dry-run : Preview changes without updating attendance records}';

    protected $description = 'Mark checked-in students without a valid time-out as Incomplete after the event check-out deadline.';

    private const EVENT_TIMEZONE = 'Asia/Manila';

    public function handle(): int
    {
        $now = Carbon::now(self::EVENT_TIMEZONE);
        $dryRun = (bool) $this->option('dry-run');
        $eligible = 0;
        $updated = 0;
        $skipped = 0;

        $this->info(($dryRun ? 'DRY RUN' : 'FINALIZATION') . ' — ' . $now->format('Y-m-d h:i:s A') . ' (Asia/Manila)');

        AttendanceRecord::query()
            ->with('event')
            ->where('status', 'Checked In')
            ->whereNull('time_out')
            ->whereNotNull('time_in')
            ->chunkById(200, function ($records) use ($now, $dryRun, &$eligible, &$updated, &$skipped) {
                foreach ($records as $record) {
                    $event = $record->event;

                    // Never infer an absence from a missing, canceled, or malformed event.
                    if (!$event || in_array(strtolower((string) $event->status), ['cancelled', 'canceled'], true)) {
                        $skipped++;
                        continue;
                    }

                    try {
                        $date = Carbon::parse($event->event_date, self::EVENT_TIMEZONE)->format('Y-m-d');
                        $startTime = Carbon::parse($event->start_time, self::EVENT_TIMEZONE)->format('H:i:s');
                        $endTime = Carbon::parse($event->end_time, self::EVENT_TIMEZONE)->format('H:i:s');

                        $startsAt = Carbon::parse("{$date} {$startTime}", self::EVENT_TIMEZONE);
                        $endsAt = Carbon::parse("{$date} {$endTime}", self::EVENT_TIMEZONE);

                        // Handle events that run across midnight.
                        if ($endsAt->lessThan($startsAt)) {
                            $endsAt->addDay();
                        }

                        $deadline = $endsAt->copy()->addHour();
                    } catch (Throwable $exception) {
                        $skipped++;
                        $this->warn("Skipped attendance #{$record->attendance_id}: invalid event schedule.");
                        continue;
                    }

                    // Exactly at the deadline, check-out is still permitted.
                    if (!$now->greaterThan($deadline)) {
                        continue;
                    }

                    $eligible++;

                    if ($dryRun) {
                        $this->line("Would finalize attendance #{$record->attendance_id}, event #{$event->event_id}; deadline {$deadline->format('Y-m-d h:i A')}.");
                        continue;
                    }

                    // Atomic conditional update: a concurrent successful time-out wins.
                    $affected = DB::table('attendance_records')
                        ->where('attendance_id', $record->attendance_id)
                        ->where('status', 'Checked In')
                        ->whereNull('time_out')
                        ->update([
                            'status' => 'Incomplete',
                            'updated_at' => now(),
                        ]);

                    $updated += $affected;
                }
            }, 'attendance_id');

        $this->info($dryRun
            ? "Dry run finished. Eligible to become Incomplete: {$eligible}. Skipped invalid/cancelled records: {$skipped}. No database changes."
            : "Finalization finished. Changed to Incomplete: {$updated}. Skipped invalid/cancelled records: {$skipped}.");

        return self::SUCCESS;
    }
}
