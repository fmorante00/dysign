<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::create([
            'permission_name' => 'manage_personnel_accounts',
            'description' => 'Create, update, activate, and deactivate personnel accounts.',
            'status' => 'Active',
        ]);

        Permission::create([
            'permission_name' => 'assign_roles',
            'description' => 'Assign and manage personnel roles and access levels.',
            'status' => 'Active',
        ]);

        Permission::create([
            'permission_name' => 'import_student_data',
            'description' => 'Import and manage official student records.',
            'status' => 'Active',
        ]);

        Permission::create([
            'permission_name' => 'manage_rfid_credentials',
            'description' => 'Assign, update, and validate RFID credentials.',
            'status' => 'Active',
        ]);

        Permission::create([
            'permission_name' => 'view_attendance',
            'description' => 'Access attendance-related information.',
            'status' => 'Active',
        ]);
    }
}