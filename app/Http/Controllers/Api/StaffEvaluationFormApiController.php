<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\EvaluationForm;
use App\Models\Event;

use App\Services\EvaluationFormImportService;

use Carbon\Carbon;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StaffEvaluationFormApiController extends Controller
{
    public function __construct(
        private EvaluationFormImportService $importService
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | LIST DEPARTMENT EVALUATION FORMS
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        $user =
            auth()->user();


        if (!$user->department_id) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Your account is not assigned to a department.',

            ], 403);
        }


        $forms =
            EvaluationForm::query()
                ->with([
                    'event',
                    'creator',
                    'questions.options',
                ])
                ->whereHas(
                    'event',
                    function ($query) use ($user) {

                        $query->where(
                            'department_id',
                            $user->department_id
                        );

                    }
                )
                ->latest(
                    'created_at'
                )
                ->get();


        return response()->json([

            'success' =>
                true,

            'count' =>
                $forms->count(),

            'data' =>
                $forms,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | IMPORT WORD EVALUATION FORM
    |--------------------------------------------------------------------------
    */

    public function import(
        Request $request
    ): JsonResponse {

        $validated =
            $request->validate([

                'event_id' => [
                    'required',
                    'integer',
                    'exists:events,event_id',
                ],

                'title' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],

                'source_file' => [
                    'required',
                    'file',
                    'mimes:docx',
                    'max:10240',
                ],

            ]);


        $event =
            Event::findOrFail(
                $validated['event_id']
            );


        $form =
            $this
                ->importService
                ->import(

                    auth()->user(),

                    $event,

                    $request->file(
                        'source_file'
                    ),

                    $validated['title']
                    ?? null,

                    $validated['description']
                    ?? null

                );


        return response()->json([

            'success' =>
                true,

            'message' =>
                'Evaluation form generated successfully and scheduled for automatic publication after the event.',

            'data' =>
                $form,

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW FORM
    |--------------------------------------------------------------------------
    */

    public function show(
        EvaluationForm $evaluationForm
    ): JsonResponse {

        $this->authorizeDepartment(
            $evaluationForm
        );


        $evaluationForm->load([
            'event',
            'creator',
            'questions.options',
        ]);


        return response()->json([

            'success' =>
                true,

            'data' =>
                $evaluationForm,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLISH EVALUATION FORM
    |--------------------------------------------------------------------------
    |
    | Automation:
    |
    | 1. Verify Department Staff ownership.
    | 2. Form must still be Draft.
    | 3. Form must contain questions.
    | 4. Event must already be finished.
    | 5. Automatically change status to Published.
    | 6. Automatically record published_at.
    |
    */

    public function publish(
        EvaluationForm $evaluationForm
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | Department Authorization
        |--------------------------------------------------------------------------
        */

        $this->authorizeDepartment(
            $evaluationForm
        );


        /*
        |--------------------------------------------------------------------------
        | Load Required Relationships
        |--------------------------------------------------------------------------
        */

        $evaluationForm->load([
            'event',
            'questions.options',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Form Must Still Be Draft
        |--------------------------------------------------------------------------
        */

        if (
            $evaluationForm->status
            !== 'Draft'
        ) {

            throw ValidationException::withMessages([

                'status' =>
                    'Only Draft evaluation forms can be published.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Form Must Contain Questions
        |--------------------------------------------------------------------------
        */

        if (
            $evaluationForm
                ->questions
                ->isEmpty()
        ) {

            throw ValidationException::withMessages([

                'questions' =>
                    'The evaluation form must contain at least one question before it can be published.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Event End Date and Time
        |--------------------------------------------------------------------------
        |
        | event_date may already contain:
        |
        | 2026-10-29 00:00:00
        |
        | Therefore we extract only Y-m-d before combining it
        | with the event's end_time.
        |
        */

        $event =
            $evaluationForm->event;


        $eventDate =
            Carbon::parse(
                $event->event_date
            )
            ->format(
                'Y-m-d'
            );


        $eventEndTime =
            Carbon::parse(
                $event->end_time
            )
            ->format(
                'H:i:s'
            );


        $eventEndsAt =
            Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $eventDate
                . ' '
                . $eventEndTime,
                'Asia/Manila'
            );


        $now =
            Carbon::now(
                'Asia/Manila'
            );


        /*
        |--------------------------------------------------------------------------
        | Evaluation Available Only After Event
        |--------------------------------------------------------------------------
        */

        if (
            $now->lt(
                $eventEndsAt
            )
        ) {

            throw ValidationException::withMessages([

                'event' =>
                    'The evaluation form cannot be published until the event has ended.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Publish
        |--------------------------------------------------------------------------
        */

        $evaluationForm->update([

            'status' =>
                'Published',

            'published_at' =>
                now(),

            'closed_at' =>
                null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Return Updated Form
        |--------------------------------------------------------------------------
        */

        $evaluationForm =
            $evaluationForm
                ->fresh()
                ->load([
                    'event',
                    'creator',
                    'questions.options',
                ]);


        return response()->json([

            'success' =>
                true,

            'message' =>
                'Evaluation form published successfully and is now available to applicable students.',

            'data' =>
                $evaluationForm,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    private function authorizeDepartment(
        EvaluationForm $evaluationForm
    ): void {

        $user =
            auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Department Staff Must Have Department
        |--------------------------------------------------------------------------
        */

        if (!$user->department_id) {

            abort(
                403,
                'Your account is not assigned to a department.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Load Event
        |--------------------------------------------------------------------------
        */

        $evaluationForm->loadMissing(
            'event'
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Cross-Department Access
        |--------------------------------------------------------------------------
        */

        if (
            (int) $evaluationForm
                ->event
                ->department_id
            !==
            (int) $user
                ->department_id
        ) {

            abort(
                403,
                'You are not authorized to access this evaluation form.'
            );
        }
    }
}