<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {

            $table->foreignId('department_id')
                ->nullable()
                ->after('created_by')
                ->constrained('departments', 'department_id')
                ->nullOnDelete();


            $table->dropColumn('department');

        });
    }



    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {

            $table->dropForeign([
                'department_id'
            ]);

            $table->dropColumn('department_id');


            $table->string('department')
                ->nullable();

        });
    }

};