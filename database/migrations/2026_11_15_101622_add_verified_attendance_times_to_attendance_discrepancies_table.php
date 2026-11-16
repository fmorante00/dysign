<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_discrepancies', function (Blueprint $table) {
            // Evidence verified by assigned Attendance Personnel.
            $table->dateTime('verified_time_in')
                ->nullable()
                ->after('verification_notes');

            $table->dateTime('verified_time_out')
                ->nullable()
                ->after('verified_time_in');

            // Preserve original and corrected Time Out for audit history.
            $table->dateTime('before_time_out')
                ->nullable()
                ->after('before_time_in');

            $table->dateTime('after_time_out')
                ->nullable()
                ->after('after_time_in');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_discrepancies', function (Blueprint $table) {
            $table->dropColumn([
                'verified_time_in',
                'verified_time_out',
                'before_time_out',
                'after_time_out',
            ]);
        });
    }
};