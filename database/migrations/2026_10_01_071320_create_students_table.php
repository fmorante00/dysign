<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {

            $table->id('student_id');

            $table->string('student_number')
                ->unique();

            $table->string('first_name');

            $table->string('middle_name')
                ->nullable();

            $table->string('last_name');


            $table->string('college');


            $table->string('program_name');

            $table->string('program_code');


            $table->integer('year_level');


            $table->enum('student_status', [
                'Regular',
                'Irregular',
                'Transferee',
                'Returning'
            ])
            ->default('Regular');


            $table->string('rfid_identifier')
                ->unique();


            $table->enum('status', [
                'Active',
                'Inactive'
            ])
            ->default('Active');


            $table->timestamps();

        });
    }



    public function down(): void
    {
        Schema::dropIfExists('students');
    }

};