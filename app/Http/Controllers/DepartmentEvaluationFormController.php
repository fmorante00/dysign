<?php

namespace App\Http\Controllers;

use App\Models\Event;

use Illuminate\View\View;

class DepartmentEvaluationFormController extends Controller
{
    public function index(): View
    {
        $user =
            auth()->user();


        if (!$user->department_id) {

            abort(
                403,
                'Your account is not assigned to a department.'
            );
        }


        $events =
            Event::query()
                ->where(
                    'department_id',
                    $user->department_id
                )
                ->where(
                    'status',
                    '!=',
                    'Cancelled'
                )
                ->whereDoesntHave(
                    'evaluationForms',
                    function ($query) {

                        $query->whereIn(
                            'status',
                            [
                                'Draft',
                                'Scheduled',
                                'Published',
                            ]
                        );

                    }
                )
                ->orderByDesc(
                    'event_date'
                )
                ->get();


        return view(
            'staff.evaluations.index',
            compact(
                'events'
            )
        );
    }
}