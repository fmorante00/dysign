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
    Schema::create('events', function (Blueprint $table) {

        $table->id('event_id');

        $table->string('event_name');

        $table->text('description')
            ->nullable();

        $table->date('event_date');

        $table->time('start_time');

        $table->time('end_time');

        $table->string('location');

        $table->foreignId('created_by')
            ->constrained('users', 'user_id')
            ->cascadeOnDelete();

        $table->string('department')
            ->nullable();

        $table->enum('status', [
            'Upcoming',
            'Ongoing',
            'Completed',
            'Cancelled'
        ])
        ->default('Upcoming');

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
