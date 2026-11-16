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
        Schema::create('attendance_discrepancy_documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->foreignId('discrepancy_id')->constrained('attendance_discrepancies', 'discrepancy_id')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_discrepancy_documents');
    }
};
