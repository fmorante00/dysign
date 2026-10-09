<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Temporarily remove student foreign key
        |--------------------------------------------------------------------------
        */

        Schema::table('announcement_recipients', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });


        /*
        |--------------------------------------------------------------------------
        | Add personnel recipient support
        |--------------------------------------------------------------------------
        */

        Schema::table('announcement_recipients', function (Blueprint $table) {

            $table->unsignedBigInteger('student_id')
                ->nullable()
                ->change();

            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->after('student_id');

            $table->enum('recipient_type', [
                'Student',
                'Personnel',
            ])
            ->default('Student')
            ->after('user_id');
        });


        /*
        |--------------------------------------------------------------------------
        | Restore relationships
        |--------------------------------------------------------------------------
        */

        Schema::table('announcement_recipients', function (Blueprint $table) {

            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();

            $table->unique(
                [
                    'announcement_id',
                    'user_id',
                ],
                'announcement_user_unique'
            );
        });
    }


    public function down(): void
    {
        Schema::table('announcement_recipients', function (Blueprint $table) {

            $table->dropUnique(
                'announcement_user_unique'
            );

            $table->dropForeign(['user_id']);

            $table->dropColumn([
                'recipient_type',
                'user_id',
            ]);
        });
    }
};