<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('role_name', 'Administrator')->first();
        $attendance = Role::where('role_name', 'Attendance Personnel')->first();
        $registrar = Role::where('role_name', 'Registrar Staff')->first();

        $admin->permissions()->attach(
            Permission::pluck('permission_id')
        );

        $attendance->permissions()->attach([
            Permission::where('permission_name', 'view_attendance')
                ->first()
                ->permission_id,
        ]);

        $registrar->permissions()->attach([
            Permission::where('permission_name', 'import_student_data')
                ->first()
                ->permission_id,
        ]);
    }
}