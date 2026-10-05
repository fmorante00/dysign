<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {

            $table->id('attendance_id');

            $table->foreignId('event_id')
                ->constrained('events', 'event_id')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students', 'student_id')
                ->cascadeOnDelete();

            $table->string('rfid_identifier');

            $table->timestamp('time_in')
                ->nullable();

            $table->enum('status', [
                'Present',
                'Late'
            ])
            ->default('Present');


            $table->foreignId('scanned_by')
                ->constrained('users', 'user_id')
                ->cascadeOnDelete();


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};