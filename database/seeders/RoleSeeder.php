<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'role_name' => 'Administrator',
            'description' => 'Full system access and management privileges.',
            'status' => 'Active',
        ]);

        Role::create([
            'role_name' => 'Attendance Personnel',
            'description' => 'Handles attendance-related operations.',
            'status' => 'Active',
        ]);

        Role::create([
            'role_name' => 'Registrar Staff',
            'description' => 'Manages official student records and information.',
            'status' => 'Active',
        ]);
    }
}