
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
        Schema::table('users', function (Blueprint $table) {

            // Link a user account to an existing student record.
            $table->foreignId('student_id')
                ->nullable()
                ->after('user_id')
                ->unique()
                ->constrained('students', 'student_id')
                ->restrictOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Remove the foreign key constraint first.
            $table->dropForeign(['student_id']);

            // Remove the unique index.
            $table->dropUnique(['student_id']);

            // Remove the student relationship column.
            $table->dropColumn('student_id');

        });
    }
};
