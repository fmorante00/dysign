<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'activity_point_rules',
            function (Blueprint $table) {

                $table->id(
                    'activity_point_rule_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Event
                |--------------------------------------------------------------------------
                |
                | One point rule configuration per event.
                |
                */

                $table->unsignedBigInteger(
                    'event_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Point Values
                |--------------------------------------------------------------------------
                |
                | Current attendance statuses supported by DySign:
                | - Present
                | - Late
                |
                */

                $table->unsignedInteger(
                    'present_points'
                )->default(10);

                $table->unsignedInteger(
                    'late_points'
                )->default(5);

                /*
                |--------------------------------------------------------------------------
                | Rule Status
                |--------------------------------------------------------------------------
                */

                $table->enum(
                    'status',
                    [
                        'Active',
                        'Inactive',
                    ]
                )->default('Active');

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
                    'event_id',
                    'apr_event_fk'
                )
                ->references(
                    'event_id'
                )
                ->on(
                    'events'
                )
                ->cascadeOnDelete();


                $table->foreign(
                    'created_by',
                    'apr_created_by_fk'
                )
                ->references(
                    'user_id'
                )
                ->on(
                    'users'
                )
                ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | Constraints
                |--------------------------------------------------------------------------
                |
                | An event should only have one activity point rule.
                |
                */

                $table->unique(
                    'event_id',
                    'apr_event_unique'
                );

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'activity_point_rules'
        );
    }
};