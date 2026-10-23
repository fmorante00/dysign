<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'evaluation_question_options',
            function (Blueprint $table) {

                $table->id(
                    'evaluation_question_option_id'
                );

                $table->unsignedBigInteger(
                    'evaluation_question_id'
                );

                $table->string(
                    'option_text'
                );

                $table->unsignedInteger(
                    'sort_order'
                )
                ->default(1);

                $table->timestamps();


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
            'evaluation_question_options'
        );
    }
};