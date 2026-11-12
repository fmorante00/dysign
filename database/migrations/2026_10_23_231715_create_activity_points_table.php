<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_points', function (Blueprint $table) {

            $table->id('activity_point_id');

            /*
            |--------------------------------------------------------------------------
            | Student
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('student_id');

            /*
            |--------------------------------------------------------------------------
            | Event
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('event_id');

            /*
            |--------------------------------------------------------------------------
            | Attendance Record
            |--------------------------------------------------------------------------
            |
            | Points originate from a valid attendance record.
            |
            */
            $table->unsignedBigInteger('attendance_id');

            /*
            |--------------------------------------------------------------------------
            | Awarded Points
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('points_awarded')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Award Information
            |--------------------------------------------------------------------------
            */
            $table->timestamp('awarded_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */
            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->restrictOnDelete();

            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->restrictOnDelete();

            $table->foreign('attendance_id')
                ->references('attendance_id')
                ->on('attendance_records')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Points
            |--------------------------------------------------------------------------
            |
            | A student should receive only one activity-point record
            | for the same event.
            |
            */
            $table->unique(
                ['event_id', 'student_id'],
                'activity_points_event_student_unique'
            );

            $table->unique(
                'attendance_id',
                'activity_points_attendance_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_points');
    }
};