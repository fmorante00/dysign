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
    Schema::create(
        'announcement_recipients',
        function (Blueprint $table) {

            $table->id('announcement_recipient_id');


            $table->unsignedBigInteger(
                'announcement_id'
            );


            $table->unsignedBigInteger(
                'student_id'
            );


            /*
            |--------------------------------------------------------------------------
            | Snapshot Email
            |--------------------------------------------------------------------------
            |
            | We store the actual address used at the time of sending.
            |
            */

            $table->string('email');


            $table->enum('status', [
                'Pending',
                'Sent',
                'Failed',
            ])
            ->default('Pending');


            $table->timestamp('sent_at')
                ->nullable();


            $table->text('error_message')
                ->nullable();


            $table->timestamps();



            $table->foreign(
                'announcement_id'
            )
            ->references(
                'announcement_id'
            )
            ->on('announcements')
            ->cascadeOnDelete();



            $table->foreign(
                'student_id'
            )
            ->references(
                'student_id'
            )
            ->on('students')
            ->cascadeOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Recipient
            |--------------------------------------------------------------------------
            */

            $table->unique(
                [
                    'announcement_id',
                    'student_id',
                ],
                'announcement_student_unique'
            );

        }
    );
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_recipients');
    }
};
