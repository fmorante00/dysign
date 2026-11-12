<?php

namespace App\Services;

use App\Models\ActivityPoint;
use App\Models\ActivityPointRule;
use App\Models\AttendanceRecord;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use LogicException;

class ActivityPointService
{
    /**
     * Calculate points based on attendance status.
     */
    public function calculatePoints(
        string $status,
        ActivityPointRule $rule
    ): int {
        return match ($status) {
            'Present' => (int) $rule->present_points,
            'Late' => (int) $rule->late_points,
            default => 0,
        };
    }

    /**
     * Synchronize activity points with validated attendance.
     *
     * A student must have completed attendance
     * before points can be awarded.
     */
    public function syncForAttendance(int $attendanceId): ?ActivityPoint
    {
        return DB::transaction(function () use ($attendanceId) {

            // Lock attendance to prevent concurrent processing.
            $attendance = AttendanceRecord::query()
                ->where('attendance_id', $attendanceId)
                ->lockForUpdate()
                ->firstOrFail();

            $event = Event::query()
                ->findOrFail($attendance->event_id);

            $rule = ActivityPointRule::query()
                ->where('event_id', $attendance->event_id)
                ->first();

            // Check whether Time-Out is already supported.
            $hasTimeOut = Schema::hasColumn(
                'attendance_records',
                'time_out'
            );

            $validAttendance =
                $event->status === 'Completed'
                && $rule !== null
                && in_array(
                    $attendance->status,
                    ['Present', 'Late'],
                    true
                )
                && $hasTimeOut
                && !empty($attendance->time_out);

            $existing = ActivityPoint::query()
                ->where('attendance_id', $attendanceId)
                ->first();

            // No longer valid: revoke previous points.
            if (!$validAttendance) {

                if ($existing) {
                    $existing->update([
                        'points_awarded' => 0,
                        'awarded_at' => null,
                    ]);
                }

                return $existing;
            }

            $points = $this->calculatePoints(
                $attendance->status,
                $rule
            );

            // One record per student per event.
            $award = ActivityPoint::query()->firstOrNew([
                'student_id' => $attendance->student_id,
                'event_id' => $attendance->event_id,
            ]);

            // Prevent silently replacing an attendance source.
            if (
                $award->exists
                && (int) $award->attendance_id !==
                   (int) $attendance->attendance_id
            ) {
                throw new LogicException(
                    'Conflicting attendance record requires review.'
                );
            }

            $shouldUpdateAwardDate =
                !$award->exists
                || (int) $award->points_awarded !== $points
                || $award->awarded_at === null;

            $award->attendance_id = $attendance->attendance_id;
            $award->points_awarded = $points;

            if ($shouldUpdateAwardDate) {
                $award->awarded_at = now();
            }

            $award->save();

            return $award;
        });
    }
}
