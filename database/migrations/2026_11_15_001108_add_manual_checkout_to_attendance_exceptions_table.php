<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_exceptions', function (Blueprint $table) {
            $table->dateTime('checked_out_at')->nullable();

            $table->foreignId('checked_out_by')
                ->nullable()
                ->constrained('users', 'user_id')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendance_exceptions', function (Blueprint $table) {
            $table->dropForeign(['checked_out_by']);
            $table->dropColumn(['checked_out_at', 'checked_out_by']);
        });
    }
};