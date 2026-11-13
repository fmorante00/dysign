<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Widen audit snapshot statuses to match attendance_records.
     * Does NOT alter the attendance records themselves or change correction behavior.
     */
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE `attendance_discrepancies`
                MODIFY COLUMN `before_status` ENUM('Checked In', 'Present', 'Late', 'Incomplete', 'Absent', 'Excused') NULL DEFAULT NULL,
                MODIFY COLUMN `after_status` ENUM('Checked In', 'Present', 'Late', 'Incomplete', 'Absent', 'Excused') NULL DEFAULT NULL"
        );
    }

    /**
     * Roll back only when existing snapshot data can be represented by the old enum.
     * Otherwise stop rather than silently truncate or lose history.
     */
    public function down(): void
    {
        $containsExtendedStatuses = DB::table('attendance_discrepancies')
            ->where(function ($query): void {
                $query->where(function ($before): void {
                    $before->whereNotNull('before_status')
                        ->whereNotIn('before_status', ['Present', 'Late']);
                })->orWhere(function ($after): void {
                    $after->whereNotNull('after_status')
                        ->whereNotIn('after_status', ['Present', 'Late']);
                });
            })
            ->exists();

        if ($containsExtendedStatuses) {
            throw new \RuntimeException(
                'Rollback blocked: discrepancy snapshots contain statuses not supported by the old enum. Preserve or migrate these snapshots before rolling back.'
            );
        }

        DB::statement(
            "ALTER TABLE `attendance_discrepancies`
                MODIFY COLUMN `before_status` ENUM('Present', 'Late') NULL DEFAULT NULL,
                MODIFY COLUMN `after_status` ENUM('Present', 'Late') NULL DEFAULT NULL"
        );
    }
};
