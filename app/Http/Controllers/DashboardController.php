<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Personnel;
use App\Models\Role;

class DashboardController extends Controller
{

    public function index()
    {

        $totalUsers = User::count();


        $activeUsers = User::where(
            'status',
            'Active'
        )->count();



        $totalPersonnel = Personnel::count();



        $totalRoles = Role::count();



        $roleDistribution = Role::withCount('users')
            ->get();



        $recentPersonnel = Personnel::with('user')
            ->latest()
            ->take(5)
            ->get();



        return view(
            'dashboard.admin',
            compact(
                'totalUsers',
                'activeUsers',
                'totalPersonnel',
                'totalRoles',
                'roleDistribution',
                'recentPersonnel'
            )
        );

    }

}