<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_forms', function (Blueprint $table) {

            $table->id('evaluation_form_id');

            $table->unsignedBigInteger('event_id');

            $table->unsignedBigInteger('created_by');

            $table->string('title');

            $table->text('description')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Source Document
            |--------------------------------------------------------------------------
            */

            $table->string('source_file_name')
                ->nullable();

            $table->string('source_file_path')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Form Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'Draft',
                'Published',
                'Closed',
            ])
            ->default('Draft');

            $table->timestamp('published_at')
                ->nullable();

            $table->timestamp('closed_at')
                ->nullable();

            $table->timestamps();


            $table
                ->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->cascadeOnDelete();


            $table
                ->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('evaluation_forms');
    }
};