<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add audit details without replacing or deleting any existing logs.
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'subject_type')) {
                $table->string('subject_type', 100)->nullable();
            }
            if (!Schema::hasColumn('activity_logs', 'subject_id')) {
                $table->string('subject_id', 100)->nullable();
            }
            if (!Schema::hasColumn('activity_logs', 'changes_before')) {
                $table->json('changes_before')->nullable();
            }
            if (!Schema::hasColumn('activity_logs', 'changes_after')) {
                $table->json('changes_after')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $columns = [];
            foreach (['subject_type', 'subject_id', 'changes_before', 'changes_after'] as $column) {
                if (Schema::hasColumn('activity_logs', $column)) {
                    $columns[] = $column;
                }
            }
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
