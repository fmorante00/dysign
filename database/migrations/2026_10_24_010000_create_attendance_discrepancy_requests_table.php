<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_discrepancy_requests', function (Blueprint $table) {
            $table->id('discrepancy_request_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('attendance_id')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();

            $table->enum('request_type', ['Missing Attendance', 'Incorrect Attendance']);
            $table->text('description');
            $table->dateTime('expected_time_in')->nullable();
            $table->dateTime('original_time_in')->nullable();
            $table->string('original_attendance_status', 50)->nullable();

            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            // Unique only while Pending; set NULL once resolved to permit new requests.
            $table->string('pending_key', 70)->nullable()->unique();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->dateTime('verified_time_in')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students')->restrictOnDelete();
            $table->foreign('event_id')->references('event_id')->on('events')->restrictOnDelete();
            $table->foreign('attendance_id')->references('attendance_id')->on('attendance_records')->nullOnDelete();
            $table->foreign('requested_by')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('reviewed_by')->references('user_id')->on('users')->nullOnDelete();

            $table->index(['student_id', 'created_at'], 'adr_student_created_idx');
            $table->index(['event_id', 'status'], 'adr_event_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_discrepancy_requests');
    }
};
