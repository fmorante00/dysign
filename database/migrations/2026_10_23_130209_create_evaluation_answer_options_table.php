<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'evaluation_answer_options',
            function (Blueprint $table) {

                $table->id(
                    'evaluation_answer_option_id'
                );

                $table->unsignedBigInteger(
                    'evaluation_answer_id'
                );

                $table->unsignedBigInteger(
                    'evaluation_question_option_id'
                );

                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Selected Choice
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    [
                        'evaluation_answer_id',
                        'evaluation_question_option_id',
                    ],
                    'evaluation_answer_selected_option_unique'
                );


                $table
                    ->foreign(
                        'evaluation_answer_id'
                    )
                    ->references(
                        'evaluation_answer_id'
                    )
                    ->on(
                        'evaluation_answers'
                    )
                    ->cascadeOnDelete();


                $table
                    ->foreign(
                        'evaluation_question_option_id'
                    )
                    ->references(
                        'evaluation_question_option_id'
                    )
                    ->on(
                        'evaluation_question_options'
                    )
                    ->cascadeOnDelete();

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'evaluation_answer_options'
        );
    }
};