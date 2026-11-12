<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MyProfileController extends Controller
{
    /**
     * Read-only view of the currently authenticated user's own profile.
     * Available to every authenticated DySign role.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user->loadMissing(['role', 'department']);

        $roleName = $user->role?->role_name ?? 'User';
        $student = null;
        $personnel = null;

        // Reuse existing relations when they exist. Do not assume a new schema.
        if ($roleName === 'Student Account') {
            if (method_exists($user, 'student')) {
                $student = $user->student;
            } elseif (
                Schema::hasTable('students') &&
                Schema::hasColumn('students', 'user_id')
            ) {
                $student = DB::table('students')
                    ->where('user_id', $user->user_id)
                    ->first();
            }
        }

        if ($roleName === 'Attendance Personnel' && method_exists($user, 'personnel')) {
            $personnel = $user->personnel;
        }

        return view('profile.my-profile', compact('user', 'roleName', 'student', 'personnel'));
    }
}
