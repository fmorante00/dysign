<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DepartmentExcuseRequestController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();


        if (!$user->department_id) {

            abort(
                403,
                'Your account is not assigned to a department.'
            );
        }


        return view(
            'staff.excuse-requests.index'
        );
    }
}