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
            'personnel_availabilities',
            function (Blueprint $table) {

                /*
                |--------------------------------------------------------------------------
                | Primary Key
                |--------------------------------------------------------------------------
                */

                $table->id(
                    'availability_id'
                );


                /*
                |--------------------------------------------------------------------------
                | Attendance Personnel
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'personnel_id'
                );


                /*
                |--------------------------------------------------------------------------
                | Availability Schedule
                |--------------------------------------------------------------------------
                */

                $table->date(
                    'availability_date'
                );

                $table->time(
                    'start_time'
                );

                $table->time(
                    'end_time'
                );


                /*
                |--------------------------------------------------------------------------
                | Availability Status
                |--------------------------------------------------------------------------
                */

                $table->enum(
                    'availability_status',
                    [
                        'Available',
                        'Unavailable',
                    ]
                )->default(
                    'Available'
                );


                /*
                |--------------------------------------------------------------------------
                | Optional Reason
                |--------------------------------------------------------------------------
                */

                $table->text(
                    'reason'
                )->nullable();


                /*
                |--------------------------------------------------------------------------
                | Timestamps
                |--------------------------------------------------------------------------
                */

                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | Foreign Key
                |--------------------------------------------------------------------------
                */

                $table->foreign(
                    'personnel_id',
                    'pa_personnel_fk'
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
                | Indexes
                |--------------------------------------------------------------------------
                |
                | Custom short names are used because MySQL limits identifier
                | names to 64 characters.
                |
                */

                $table->index(
                    [
                        'personnel_id',
                        'availability_date',
                    ],
                    'pa_personnel_date_idx'
                );


                $table->index(
                    [
                        'availability_date',
                        'availability_status',
                    ],
                    'pa_date_status_idx'
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
            'personnel_availabilities'
        );
    }
};