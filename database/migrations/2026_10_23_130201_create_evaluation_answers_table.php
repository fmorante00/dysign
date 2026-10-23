<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'evaluation_answers',
            function (Blueprint $table) {

                $table->id(
                    'evaluation_answer_id'
                );

                $table->unsignedBigInteger(
                    'evaluation_submission_id'
                );

                $table->unsignedBigInteger(
                    'evaluation_question_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Text Answers
                |--------------------------------------------------------------------------
                |
                | Used for:
                | Short Answer
                | Paragraph
                |
                */

                $table->text(
                    'answer_text'
                )
                ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Numeric Answers
                |--------------------------------------------------------------------------
                |
                | Used mainly for Rating questions.
                |
                */

                $table->integer(
                    'answer_number'
                )
                ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Boolean Answers
                |--------------------------------------------------------------------------
                |
                | Used for Yes / No questions.
                |
                */

                $table->boolean(
                    'answer_boolean'
                )
                ->nullable();


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | One Answer Record Per Question
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    [
                        'evaluation_submission_id',
                        'evaluation_question_id',
                    ],
                    'evaluation_submission_question_unique'
                );


                $table
                    ->foreign(
                        'evaluation_submission_id'
                    )
                    ->references(
                        'evaluation_submission_id'
                    )
                    ->on(
                        'evaluation_submissions'
                    )
                    ->cascadeOnDelete();


                $table
                    ->foreign(
                        'evaluation_question_id'
                    )
                    ->references(
                        'evaluation_question_id'
                    )
                    ->on(
                        'evaluation_questions'
                    )
                    ->cascadeOnDelete();

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'evaluation_answers'
        );
    }
};