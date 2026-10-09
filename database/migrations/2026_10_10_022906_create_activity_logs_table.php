<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {

            $table->id('activity_log_id');

            $table->unsignedBigInteger('user_id')
                ->nullable();

            $table->string('action');

            $table->string('module');

            $table->text('description')
                ->nullable();

            $table->enum(
                'status',
                [
                    'Success',
                    'Failed',
                ]
            )
            ->default('Success');

            $table->string(
                'ip_address',
                45
            )
            ->nullable();

            $table->text('user_agent')
                ->nullable();

            $table->timestamps();


            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();


            $table->index('module');

            $table->index('status');

            $table->index('created_at');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'activity_logs'
        );
    }
};