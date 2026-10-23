<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'excuse_requests',
            function (Blueprint $table) {

                $table->id(
                    'excuse_request_id'
                );


                $table->unsignedBigInteger(
                    'event_id'
                );


                $table->unsignedBigInteger(
                    'student_id'
                );


                /*
                |--------------------------------------------------------------------------
                | Request Timing
                |--------------------------------------------------------------------------
                |
                | Automatically determined by the system:
                |
                | Pre-Event  = student informs before the event
                | Post-Event = student explains absence after the event
                |
                */

                $table->enum(
                    'request_timing',
                    [
                        'Pre-Event',
                        'Post-Event',
                    ]
                );


                $table->text(
                    'reason'
                );


                /*
                |--------------------------------------------------------------------------
                | Review Status
                |--------------------------------------------------------------------------
                |
                | Module #11 creates Pending requests.
                | Module #12 will approve/reject them.
                |
                */

                $table->enum(
                    'status',
                    [
                        'Pending',
                        'Approved',
                        'Rejected',
                    ]
                )
                ->default(
                    'Pending'
                );


                /*
                |--------------------------------------------------------------------------
                | Module #12 Review Fields
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'reviewed_by'
                )
                ->nullable();


                $table->text(
                    'review_remarks'
                )
                ->nullable();


                $table->timestamp(
                    'reviewed_at'
                )
                ->nullable();


                $table->timestamp(
                    'submitted_at'
                )
                ->nullable();


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Excuse Requests
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    [
                        'event_id',
                        'student_id',
                    ],
                    'excuse_event_student_unique'
                );


                /*
                |--------------------------------------------------------------------------
                | Foreign Keys
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreign(
                        'event_id'
                    )
                    ->references(
                        'event_id'
                    )
                    ->on(
                        'events'
                    )
                    ->cascadeOnDelete();


                $table
                    ->foreign(
                        'student_id'
                    )
                    ->references(
                        'student_id'
                    )
                    ->on(
                        'students'
                    )
                    ->cascadeOnDelete();


                $table
                    ->foreign(
                        'reviewed_by'
                    )
                    ->references(
                        'user_id'
                    )
                    ->on(
                        'users'
                    )
                    ->nullOnDelete();

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'excuse_requests'
        );
    }
};