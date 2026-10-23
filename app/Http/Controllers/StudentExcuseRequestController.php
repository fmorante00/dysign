<?php

namespace App\Http\Controllers;

use App\Models\Event;

use Illuminate\View\View;

class StudentExcuseRequestController extends Controller
{
    public function index(): View
    {
        $user =
            auth()->user();


        if (!$user->student_id) {

            abort(
                403,
                'No student record is linked to this account.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Events Applicable To Student
        |--------------------------------------------------------------------------
        |
        | Only events where the student appears in the generated
        | event eligibility list are displayed.
        |
        | Completed events are intentionally included because a student
        | may submit a Post-Event excuse request.
        |
        */

        $events =
            Event::query()
                ->whereHas(
                    'eligibleStudents',
                    function ($query) use ($user) {

                        $query->where(
                            'student_id',
                            $user->student_id
                        );

                    }
                )
                ->where(
                    'status',
                    '!=',
                    'Cancelled'
                )
                ->orderByDesc(
                    'event_date'
                )
                ->get();


        return view(
            'student.excuse-requests.index',
            compact(
                'events'
            )
        );
    }
}