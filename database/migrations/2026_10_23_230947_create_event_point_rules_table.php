<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'event_point_rules',
            function (Blueprint $table) {

                $table->id(
                    'activity_point_rule_id'
                );


                /*
                |--------------------------------------------------------------------------
                | Event
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'event_id'
                )->unique();


                /*
                |--------------------------------------------------------------------------
                | Point Value
                |--------------------------------------------------------------------------
                |
                | Number of activity points awarded after
                | valid participation is completed.
                |
                */

                $table->unsignedInteger(
                    'points'
                )->default(1);


                /*
                |--------------------------------------------------------------------------
                | Created By
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'created_by'
                )->nullable();


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | Foreign Keys
                |--------------------------------------------------------------------------
                */

                $table->foreign(
                    'event_id'
                )
                ->references(
                    'event_id'
                )
                ->on(
                    'events'
                )
                ->cascadeOnDelete();


                $table->foreign(
                    'created_by'
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
            'event_point_rules'
        );
    }
};