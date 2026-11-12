<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id('registration_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students', 'student_id')->restrictOnDelete();
            $table->foreignId('registered_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->enum('source', ['Student', 'Department Staff'])->default('Student');
            $table->enum('status', ['Registered', 'Cancelled'])->default('Registered');
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'student_id'], 'event_registration_event_student_unique');
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
