<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {

            $table->id('announcement_id');

            $table->unsignedBigInteger('event_id');

            $table->unsignedBigInteger('created_by');

            $table->string('subject');

            $table->text('message');

            $table->enum('audience_type', [
                'All',
                'College',
                'Year',
                'CollegeYear',
                'Import',
            ]);

            $table->string('college')->nullable();

            $table->integer('year_level')->nullable();

            $table->string('image_path')->nullable();

            $table->unsignedInteger('recipient_count')
                ->default(0);

            $table->enum('status', [
                'Processing',
                'Sent',
                'Partially Sent',
                'Failed',
            ])
            ->default('Processing');

            $table->timestamp('sent_at')->nullable();

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
