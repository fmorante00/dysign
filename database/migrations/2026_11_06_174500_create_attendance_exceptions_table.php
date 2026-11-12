<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_exceptions', function (Blueprint $table) {
            $table->id('exception_id');

            $table->foreignId('event_id')->constrained('events', 'event_id')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users', 'user_id')->restrictOnDelete();
            $table->enum('issue_type', ['Missed Scan', 'Unreadable RFID', 'Scanner Failure', 'RFID Not Registered']);
            $table->dateTime('observed_at');
            $table->text('reason');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            $table->foreignId('reviewed_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->foreignId('attendance_id')->nullable()->constrained('attendance_records', 'attendance_id')->nullOnDelete();

            $table->timestamps();
            $table->index(['event_id', 'status']);
            $table->index(['student_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_exceptions');
    }
};
