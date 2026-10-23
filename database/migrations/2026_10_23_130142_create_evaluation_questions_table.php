<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_questions', function (Blueprint $table) {

            $table->id('evaluation_question_id');

            $table->unsignedBigInteger(
                'evaluation_form_id'
            );

            $table->text('question_text');

            /*
            |--------------------------------------------------------------------------
            | Supported Question Types
            |--------------------------------------------------------------------------
            */

            $table->enum('question_type', [
                'Short Answer',
                'Paragraph',
                'Single Choice',
                'Multiple Choice',
                'Rating',
                'Yes No',
            ]);

            $table->boolean('is_required')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Rating Configuration
            |--------------------------------------------------------------------------
            */

            $table->unsignedTinyInteger('rating_min')
                ->nullable();

            $table->unsignedTinyInteger('rating_max')
                ->nullable();

            $table->string('rating_min_label')
                ->nullable();

            $table->string('rating_max_label')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Question Order
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('sort_order')
                ->default(1);

            $table->timestamps();


            $table
                ->foreign('evaluation_form_id')
                ->references('evaluation_form_id')
                ->on('evaluation_forms')
                ->cascadeOnDelete();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'evaluation_questions'
        );
    }
};