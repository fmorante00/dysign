<?php

namespace App\Services;

use App\Models\ExcuseRequest;
use App\Models\User;

use Illuminate\Validation\ValidationException;

class ExcuseReviewService
{
    public function review(
        User $reviewer,
        ExcuseRequest $excuseRequest,
        array $data
    ): ExcuseRequest {

        /*
        |--------------------------------------------------------------------------
        | Request Must Still Be Pending
        |--------------------------------------------------------------------------
        */

        if (
            $excuseRequest->status
            !== 'Pending'
        ) {

            throw ValidationException::withMessages([

                'decision' =>
                    'This excuse request has already been reviewed.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Department Authorization
        |--------------------------------------------------------------------------
        */

        $event =
            $excuseRequest->event;


        if (!$reviewer->department_id) {

            abort(
                403,
                'Your account is not assigned to a department.'
            );
        }


        if (
            (int) $event->department_id
            !==
            (int) $reviewer->department_id
        ) {

            abort(
                403,
                'You are not authorized to review excuse requests from another department.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Save Review
        |--------------------------------------------------------------------------
        */

        $excuseRequest->update([

            'status' =>
                $data['decision'],

            'reviewed_by' =>
                $reviewer->user_id,

            'review_remarks' =>
                $data['remarks']
                ?? null,

            'reviewed_at' =>
                now(),

        ]);


        return $excuseRequest
            ->fresh()
            ->load([
                'event',
                'student',
                'documents',
                'reviewer',
            ]);
    }
}