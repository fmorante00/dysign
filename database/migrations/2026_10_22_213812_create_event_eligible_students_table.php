<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_eligible_students', function (Blueprint $table) {

            $table->id('eligible_student_id');

            $table->unsignedBigInteger('event_id');

            $table->unsignedBigInteger('student_id');

            $table->enum(
                'participation_type',
                [
                    'Required',
                    'Optional',
                ]
            )->default('Required');

            $table->timestamps();


            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->cascadeOnDelete();


            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->cascadeOnDelete();


            /*
             * One student should only appear once
             * in the generated list for an event.
             */
            $table->unique([
                'event_id',
                'student_id',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'event_eligible_students'
        );
    }
};