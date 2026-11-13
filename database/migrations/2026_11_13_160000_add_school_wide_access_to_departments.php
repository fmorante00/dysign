<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('departments', 'is_school_wide')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->boolean('is_school_wide')->default(false);
            });
        }

        // Keep the SSC record available even if the earlier department
        // seeding migration has not yet been executed locally.
        if (!DB::table('departments')->where('department_name', 'Supreme Student Council')->exists()) {
            DB::table('departments')->insert([
                'department_name' => 'Supreme Student Council',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Explicit flag: SSC student visibility is school-wide.
        // This does NOT grant access to other departments' events.
        DB::table('departments')
            ->where('department_name', 'Supreme Student Council')
            ->update([
                'is_school_wide' => true,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Do not delete SSC or any department on rollback.
        if (Schema::hasColumn('departments', 'is_school_wide')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->dropColumn('is_school_wide');
            });
        }
    }
};
