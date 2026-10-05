<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_assignments', function (Blueprint $table) {

            $table->id('assignment_id');

            $table->foreignId('event_id')
                ->constrained('events', 'event_id')
                ->cascadeOnDelete();

            $table->foreignId('personnel_id')
                ->constrained('personnel', 'personnel_id')
                ->cascadeOnDelete();

            $table->foreignId('assigned_by')
                ->constrained('users', 'user_id')
                ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_assignments');
    }
};