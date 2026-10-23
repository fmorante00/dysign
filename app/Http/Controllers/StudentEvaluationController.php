<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StudentEvaluationController extends Controller
{
    public function index(): View
    {
        $user =
            auth()->user();

        if (!$user->student_id) {

            abort(
                403,
                'Your account is not linked to a student record.'
            );

        }

        return view(
            'student.evaluations.index'
        );
    }
}