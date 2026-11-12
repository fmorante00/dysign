<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfid_card_assignments', function (Blueprint $table) {
            $table->id('rfid_card_assignment_id');
            $table->unsignedBigInteger('student_id');
            // A UID is never reused, even after loss or replacement.
            $table->string('rfid_identifier')->unique();
            $table->enum('status', ['Active', 'Lost', 'Replaced'])->default('Active');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->string('reason', 500)->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->unsignedBigInteger('ended_by')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            // Prevent deletion of a student while RFID audit history exists.
            $table->foreign('student_id')->references('student_id')->on('students')->restrictOnDelete();
            $table->foreign('assigned_by')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('ended_by')->references('user_id')->on('users')->nullOnDelete();
        });

        // Register existing cards without modifying official student or attendance data.
        DB::table('students')
            ->select(['student_id', 'rfid_identifier'])
            ->orderBy('student_id')
            ->chunk(100, function ($students): void {
                $rows = [];
                foreach ($students as $student) {
                    if ($student->rfid_identifier === null || trim((string) $student->rfid_identifier) === '') {
                        continue;
                    }
                    $rows[] = [
                        'student_id' => $student->student_id,
                        'rfid_identifier' => $student->rfid_identifier,
                        'status' => 'Active',
                        'assigned_at' => now(),
                        'reason' => 'Existing card registered during initial RFID lifecycle setup.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if ($rows !== []) {
                    DB::table('rfid_card_assignments')->insert($rows);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_card_assignments');
    }
};
