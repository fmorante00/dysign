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
        Schema::table(
            'personnel_availabilities',
            function (Blueprint $table) {

                if (
                    Schema::hasColumn(
                        'personnel_availabilities',
                        'availability_status'
                    )
                ) {
                    $table->dropColumn(
                        'availability_status'
                    );
                }

            }
        );
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'personnel_availabilities',
            function (Blueprint $table) {

                $table->enum(
                    'availability_status',
                    [
                        'Available',
                        'Unavailable',
                    ]
                )
                ->default('Unavailable')
                ->after('end_time');

            }
        );
    }
};