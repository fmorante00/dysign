<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'activity_points',
            function (Blueprint $table) {

                $table->id(
                    'activity_point_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Student
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'student_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Event
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'event_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Source Attendance Record
                |--------------------------------------------------------------------------
                |
                | Every automatically awarded point record must come from
                | a valid attendance record.
                |
                */

                $table->unsignedBigInteger(
                    'attendance_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Applied Rule
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'activity_point_rule_id'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Attendance Classification
                |--------------------------------------------------------------------------
                */

                $table->enum(
                    'attendance_status',
                    [
                        'Present',
                        'Late',
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Awarded Points
                |--------------------------------------------------------------------------
                */

                $table->unsignedInteger(
                    'points_awarded'
                );

                $table->timestamp(
                    'awarded_at'
                );

                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | Foreign Keys
                |--------------------------------------------------------------------------
                */

                $table->foreign(
                    'student_id',
                    'ap_student_fk'
                )
                ->references(
                    'student_id'
                )
                ->on(
                    'students'
                )
                ->cascadeOnDelete();


                $table->foreign(
                    'event_id',
                    'ap_event_fk'
                )
                ->references(
                    'event_id'
                )
                ->on(
                    'events'
                )
                ->cascadeOnDelete();


                $table->foreign(
                    'attendance_id',
                    'ap_attendance_fk'
                )
                ->references(
                    'attendance_id'
                )
                ->on(
                    'attendance_records'
                )
                ->cascadeOnDelete();


                $table->foreign(
                    'activity_point_rule_id',
                    'ap_rule_fk'
                )
                ->references(
                    'activity_point_rule_id'
                )
                ->on(
                    'activity_point_rules'
                )
                ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | Duplicate Protection
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    'attendance_id',
                    'ap_attendance_unique'
                );

                $table->unique(
                    [
                        'student_id',
                        'event_id',
                    ],
                    'ap_student_event_unique'
                );

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'activity_points'
        );
    }
};