<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_eligibility_rules', function (Blueprint $table) {

            $table->id('eligibility_rule_id');

            $table->unsignedBigInteger('event_id');

            $table->string('college')->nullable();

            $table->string('program_code', 100)->nullable();

            $table->unsignedTinyInteger('year_level')->nullable();

            $table->enum(
                'participation_type',
                [
                    'Required',
                    'Optional',
                ]
            )->default('Required');

            $table->unsignedBigInteger('created_by');

            $table->timestamps();


            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->cascadeOnDelete();


            $table->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'event_eligibility_rules'
        );
    }
};