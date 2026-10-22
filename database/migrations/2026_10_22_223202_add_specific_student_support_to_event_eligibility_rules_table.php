<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'event_eligibility_rules',
            function (Blueprint $table) {

                $table->enum(
                    'rule_type',
                    [
                        'Criteria',
                        'Specific',
                    ]
                )
                ->default('Criteria')
                ->after('event_id');

                $table->foreignId(
                    'student_id'
                )
                ->nullable()
                ->after('year_level')
                ->constrained(
                    'students',
                    'student_id'
                )
                ->nullOnDelete();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'event_eligibility_rules',
            function (Blueprint $table) {

                $table->dropConstrainedForeignId(
                    'student_id'
                );

                $table->dropColumn(
                    'rule_type'
                );
            }
        );
    }
};