<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add checkout time without altering or deleting any existing attendance records.
        if (!Schema::hasColumn('attendance_records', 'time_out')) {
            Schema::table('attendance_records', function (Blueprint $table) {
                $table->timestamp('time_out')->nullable()->after('time_in');
            });
        }

        // MySQL ENUM: keep existing Present/Late values and add the lifecycle statuses.
        // New scans default to Checked In, never Present before a verified checkout.
        DB::statement("ALTER TABLE `attendance_records` MODIFY COLUMN `status` ENUM('Checked In', 'Present', 'Late', 'Incomplete', 'Absent', 'Excused') NOT NULL DEFAULT 'Checked In'");

        // An official Absent/Excused entry may not have an RFID tap or scanning user.
        // Existing RFID and scanned_by values remain untouched.
        DB::statement('ALTER TABLE `attendance_records` MODIFY COLUMN `rfid_identifier` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `attendance_records` MODIFY COLUMN `scanned_by` BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Refuse rollback rather than silently discard real lifecycle states or checkout times.
        $hasNewStatuses = DB::table('attendance_records')
            ->whereNotIn('status', ['Present', 'Late'])
            ->exists();

        $hasNewNullFields = DB::table('attendance_records')
            ->where(function ($query) {
                $query->whereNull('rfid_identifier')->orWhereNull('scanned_by');
            })
            ->exists();

        $hasRecordedCheckouts = Schema::hasColumn('attendance_records', 'time_out')
            && DB::table('attendance_records')->whereNotNull('time_out')->exists();

        if ($hasNewStatuses || $hasNewNullFields || $hasRecordedCheckouts) {
            throw new RuntimeException('Rollback stopped to protect attendance status, RFID, or checkout data. Reconcile those records first.');
        }

        DB::statement("ALTER TABLE `attendance_records` MODIFY COLUMN `status` ENUM('Present', 'Late') NOT NULL DEFAULT 'Present'");
        DB::statement('ALTER TABLE `attendance_records` MODIFY COLUMN `rfid_identifier` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `attendance_records` MODIFY COLUMN `scanned_by` BIGINT UNSIGNED NOT NULL');

        if (Schema::hasColumn('attendance_records', 'time_out')) {
            Schema::table('attendance_records', function (Blueprint $table) {
                $table->dropColumn('time_out');
            });
        }
    }
};
