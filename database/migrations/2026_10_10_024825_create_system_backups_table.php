<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_backups', function (Blueprint $table) {

            $table->id('backup_id');

            $table->unsignedBigInteger('created_by')
                ->nullable();

            $table->string('filename');

            $table->string('file_path');

            $table->unsignedBigInteger('size_bytes')
                ->default(0);

            $table->enum('status', [
                'Processing',
                'Completed',
                'Failed',
            ])->default('Processing');

            $table->text('error_message')
                ->nullable();

            $table->timestamps();


            $table->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();

            $table->index('status');

            $table->index('created_at');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('system_backups');
    }
};