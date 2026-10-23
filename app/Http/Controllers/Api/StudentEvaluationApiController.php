<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\EvaluationForm;
use App\Models\EvaluationSubmission;

use App\Services\StudentEvaluationResponseService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentEvaluationApiController extends Controller
{
    public function __construct(
        private StudentEvaluationResponseService $service
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | AVAILABLE EVALUATIONS
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        $user =
            auth()->user();


        if (!$user->student_id) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Your account is not linked to a student record.',

            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | Get Events Student Is Eligible For
        |--------------------------------------------------------------------------
        */

        $eligibleEventIds =
            DB::table(
                'event_eligible_students'
            )
            ->where(
                'student_id',
                $user->student_id
            )
            ->pluck(
                'event_id'
            );


        /*
        |--------------------------------------------------------------------------
        | Published Evaluation Forms
        |--------------------------------------------------------------------------
        */

        $forms =
            EvaluationForm::query()
                ->with([
                    'event',
                    'questions.options',
                ])
                ->where(
                    'status',
                    'Published'
                )
                ->whereIn(
                    'event_id',
                    $eligibleEventIds
                )
                ->latest(
                    'published_at'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Add Submitted Status
        |--------------------------------------------------------------------------
        */

        $submittedFormIds =
            EvaluationSubmission::query()
                ->where(
                    'student_id',
                    $user->student_id
                )
                ->pluck(
                    'evaluation_form_id'
                )
                ->map(
                    fn ($id) =>
                        (int) $id
                )
                ->all();


        $forms->each(
            function ($form) use (
                $submittedFormIds
            ) {

                $form->setAttribute(
                    'already_submitted',
                    in_array(
                        (int) $form->evaluation_form_id,
                        $submittedFormIds,
                        true
                    )
                );

            }
        );


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
    | VIEW ONE EVALUATION
    |--------------------------------------------------------------------------
    */

    public function show(
        EvaluationForm $evaluationForm
    ): JsonResponse {

        $user =
            auth()->user();


        $this->authorizeStudent(
            $evaluationForm
        );


        $evaluationForm->load([
            'event',
            'questions.options',
        ]);


        $alreadySubmitted =
            EvaluationSubmission::query()
                ->where(
                    'evaluation_form_id',
                    $evaluationForm->evaluation_form_id
                )
                ->where(
                    'student_id',
                    $user->student_id
                )
                ->exists();


        $evaluationForm->setAttribute(
            'already_submitted',
            $alreadySubmitted
        );


        return response()->json([

            'success' =>
                true,

            'data' =>
                $evaluationForm,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT RESPONSES
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        EvaluationForm $evaluationForm
    ): JsonResponse {

        $this->authorizeStudent(
            $evaluationForm
        );


        $validated =
            $request->validate([

                'answers' => [
                    'required',
                    'array',
                ],

                'answers.*.question_id' => [
                    'required',
                    'integer',
                ],

                'answers.*.value' => [
                    'nullable',
                ],

                'answers.*.option_ids' => [
                    'nullable',
                    'array',
                ],

                'answers.*.option_ids.*' => [
                    'integer',
                ],

            ]);


        $submission =
            $this
                ->service
                ->submit(

                    auth()->user(),

                    $evaluationForm,

                    $validated['answers']

                );


        return response()->json([

            'success' =>
                true,

            'message' =>
                'Evaluation submitted successfully. Thank you for your feedback.',

            'data' =>
                $submission,

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    private function authorizeStudent(
        EvaluationForm $evaluationForm
    ): void {

        $user =
            auth()->user();


        if (!$user->student_id) {

            abort(
                403,
                'Your account is not linked to a student record.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Evaluation Must Be Published
        |--------------------------------------------------------------------------
        */

        if (
            $evaluationForm->status
            !== 'Published'
        ) {

            abort(
                404,
                'Evaluation form not available.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Student Must Belong To Event Eligibility
        |--------------------------------------------------------------------------
        */

        $eligible =
            DB::table(
                'event_eligible_students'
            )
            ->where(
                'event_id',
                $evaluationForm->event_id
            )
            ->where(
                'student_id',
                $user->student_id
            )
            ->exists();


        if (!$eligible) {

            abort(
                403,
                'You are not eligible to access this evaluation.'
            );
        }
    }

    public function response(EvaluationForm $evaluationForm)
{
    $user = auth()->user();

    if (!$user->student_id) {
        abort(
            403,
            'Your account is not linked to a student record.'
        );
    }

    $submission =
        \App\Models\EvaluationSubmission::query()
            ->with([
                'answers.question',
                'answers.selectedOptions.option',
            ])
            ->where(
                'evaluation_form_id',
                $evaluationForm->evaluation_form_id
            )
            ->where(
                'student_id',
                $user->student_id
            )
            ->first();

    if (!$submission) {
        return response()->json([
            'success' => false,
            'message' => 'You have not submitted a response for this evaluation.',
        ], 404);
    }

    $answers =
        $submission->answers
            ->map(function ($answer) {

                $question =
                    $answer->question;

                $value = null;

                switch ($question->question_type) {

                    case 'Rating':

                        $value =
                            $answer->answer_number;

                        break;


                    case 'Yes No':

                        $value =
                            $answer->answer_boolean
                                ? 'Yes'
                                : 'No';

                        break;


                    case 'Single Choice':
                    case 'Multiple Choice':

                        $value =
                            $answer->selectedOptions
                                ->map(
                                    fn ($selected) =>
                                        $selected->option?->option_text
                                )
                                ->filter()
                                ->values()
                                ->toArray();

                        break;


                    case 'Short Answer':
                    case 'Paragraph':

                        $value =
                            $answer->answer_text;

                        break;
                }


                return [
                    'question_id' =>
                        $question->evaluation_question_id,

                    'question' =>
                        $question->question_text,

                    'type' =>
                        $question->question_type,

                    'answer' =>
                        $value,
                ];
            })
            ->values();


    return response()->json([
        'success' => true,

        'data' => [
            'submission_id' =>
                $submission->evaluation_submission_id,

            'evaluation_form_id' =>
                $evaluationForm->evaluation_form_id,

            'title' =>
                $evaluationForm->title,

            'submitted_at' =>
                $submission->submitted_at,

            'answers' =>
                $answers,
        ],
    ]);
}
}