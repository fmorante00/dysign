<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $departments = [
            'College of Accountancy',
            'College of Business Administration',
            'College of Computer Studies',
            'College of Arts and Sciences',
            'College of Education',
            'College of Health Sciences',
            'College of Hospitality Management and Tourism',
            'College of Maritime Education',
            'School of Mechanical Engineering',
            'School of Psychology',
            'Supreme Student Council',
        ];

        foreach ($departments as $department) {
            $exists = DB::table('departments')
                ->where('department_name', $department)
                ->exists();

            if (!$exists) {
                DB::table('departments')->insert([
                    'department_name' => $department,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Do not delete departments on rollback.
        // Existing users and students may reference them.
    }
};
