<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'personnel_duties',
            function (Blueprint $table) {

                /*
                |--------------------------------------------------------------------------
                | Primary Key
                |--------------------------------------------------------------------------
                */

                $table->id(
                    'duty_id'
                );


                /*
                |--------------------------------------------------------------------------
                | Related Assignment
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'assignment_id'
                );

                $table->unsignedBigInteger(
                    'event_id'
                );

                $table->unsignedBigInteger(
                    'personnel_id'
                );


                /*
                |--------------------------------------------------------------------------
                | Duty Attendance
                |--------------------------------------------------------------------------
                */

                $table->dateTime(
                    'check_in_at'
                )->nullable();

                $table->dateTime(
                    'check_out_at'
                )->nullable();


                /*
                |--------------------------------------------------------------------------
                | Duty Status
                |--------------------------------------------------------------------------
                |
                | Assigned
                |     Personnel has an event assignment but has not checked in.
                |
                | On Duty
                |     Personnel has checked in but has not checked out.
                |
                | Completed
                |     Both check-in and check-out were recorded.
                |
                | Missed
                |     Event ended without a personnel check-in.
                |
                | Incomplete
                |     Event ended after check-in but without check-out.
                |
                */

                $table->enum(
                    'duty_status',
                    [
                        'Assigned',
                        'On Duty',
                        'Completed',
                        'Missed',
                        'Incomplete',
                    ]
                )->default(
                    'Assigned'
                );


                /*
                |--------------------------------------------------------------------------
                | Completed Duty Minutes
                |--------------------------------------------------------------------------
                */

                $table->unsignedInteger(
                    'completed_minutes'
                )->nullable();


                /*
                |--------------------------------------------------------------------------
                | Timestamps
                |--------------------------------------------------------------------------
                */

                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | Foreign Keys
                |--------------------------------------------------------------------------
                */

                $table->foreign(
                    'assignment_id',
                    'pd_assignment_fk'
                )
                ->references(
                    'assignment_id'
                )
                ->on(
                    'event_assignments'
                )
                ->cascadeOnDelete();


                $table->foreign(
                    'event_id',
                    'pd_event_fk'
                )
                ->references(
                    'event_id'
                )
                ->on(
                    'events'
                )
                ->cascadeOnDelete();


                $table->foreign(
                    'personnel_id',
                    'pd_personnel_fk'
                )
                ->references(
                    'personnel_id'
                )
                ->on(
                    'personnel'
                )
                ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | Constraints
                |--------------------------------------------------------------------------
                |
                | One duty record per personnel assignment.
                |
                */

                $table->unique(
                    'assignment_id',
                    'pd_assignment_unique'
                );


                /*
                |--------------------------------------------------------------------------
                | Indexes
                |--------------------------------------------------------------------------
                */

                $table->index(
                    [
                        'event_id',
                        'duty_status',
                    ],
                    'pd_event_status_idx'
                );


                $table->index(
                    [
                        'personnel_id',
                        'duty_status',
                    ],
                    'pd_personnel_status_idx'
                );

            }
        );
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'personnel_duties'
        );
    }
};