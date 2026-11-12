<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_discrepancies', function (Blueprint $table) {
            $table->id('discrepancy_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete();
            $table->enum('request_type', ['Missing Attendance', 'Incorrect Status', 'Incorrect Time']);
            $table->enum('requested_status', ['Present', 'Late'])->nullable();
            $table->dateTime('requested_time_in')->nullable();
            $table->text('reason');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            // Optional evidence from personnel who were actually assigned to the event.
            $table->enum('verification_status', ['Unverified', 'Confirmed', 'Not Confirmed'])->default('Unverified');
            $table->foreignId('verified_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            // Review and immutable before/after snapshots preserve an audit trail.
            $table->foreignId('reviewed_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->foreignId('attendance_id')->nullable()->constrained('attendance_records', 'attendance_id')->nullOnDelete();
            $table->enum('before_status', ['Present', 'Late'])->nullable();
            $table->dateTime('before_time_in')->nullable();
            $table->enum('after_status', ['Present', 'Late'])->nullable();
            $table->dateTime('after_time_in')->nullable();
            $table->timestamps();
            $table->index(['student_id', 'event_id', 'status']);
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_discrepancies');
    }
};
