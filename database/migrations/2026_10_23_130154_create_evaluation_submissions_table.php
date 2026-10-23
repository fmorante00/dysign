<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'evaluation_submissions',
            function (Blueprint $table) {

                $table->id(
                    'evaluation_submission_id'
                );

                $table->unsignedBigInteger(
                    'evaluation_form_id'
                );

                $table->unsignedBigInteger(
                    'student_id'
                );

                $table->timestamp(
                    'submitted_at'
                )
                ->nullable();

                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Submission
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    [
                        'evaluation_form_id',
                        'student_id',
                    ],
                    'evaluation_form_student_unique'
                );


                $table
                    ->foreign(
                        'evaluation_form_id'
                    )
                    ->references(
                        'evaluation_form_id'
                    )
                    ->on(
                        'evaluation_forms'
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

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'evaluation_submissions'
        );
    }
};