<?php

namespace App\Services;

use App\Models\EvaluationAnswer;
use App\Models\EvaluationAnswerOption;
use App\Models\EvaluationForm;
use App\Models\EvaluationSubmission;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentEvaluationResponseService
{
    /*
    |--------------------------------------------------------------------------
    | SUBMIT EVALUATION
    |--------------------------------------------------------------------------
    */

    public function submit(
        User $user,
        EvaluationForm $form,
        array $answers
    ): EvaluationSubmission {

        /*
        |--------------------------------------------------------------------------
        | Student Account Must Be Linked To Student
        |--------------------------------------------------------------------------
        */

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
            $form->status
            !== 'Published'
        ) {

            throw ValidationException::withMessages([

                'evaluation' =>
                    'This evaluation form is not currently available.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Student Must Be Eligible For Event
        |--------------------------------------------------------------------------
        */

        $eligible =
            DB::table(
                'event_eligible_students'
            )
            ->where(
                'event_id',
                $form->event_id
            )
            ->where(
                'student_id',
                $user->student_id
            )
            ->exists();


        if (!$eligible) {

            abort(
                403,
                'You are not eligible to answer this event evaluation.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Submission
        |--------------------------------------------------------------------------
        */

        $alreadySubmitted =
            EvaluationSubmission::query()
                ->where(
                    'evaluation_form_id',
                    $form->evaluation_form_id
                )
                ->where(
                    'student_id',
                    $user->student_id
                )
                ->exists();


        if ($alreadySubmitted) {

            throw ValidationException::withMessages([

                'evaluation' =>
                    'You have already submitted this evaluation.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Load Questions
        |--------------------------------------------------------------------------
        */

        $form->load(
            'questions.options'
        );


        /*
        |--------------------------------------------------------------------------
        | Convert Incoming Answers To Question-ID Map
        |--------------------------------------------------------------------------
        */

        $answerMap = [];


        foreach (
            $answers
            as $answer
        ) {

            if (
                isset(
                    $answer['question_id']
                )
            ) {

                $answerMap[
                    (int) $answer['question_id']
                ] =
                    $answer;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Every Question
        |--------------------------------------------------------------------------
        */

        foreach (
            $form->questions
            as $question
        ) {

            $incoming =
                $answerMap[
                    $question->evaluation_question_id
                ]
                ?? null;


            /*
            |--------------------------------------------------------------------------
            | Required Question
            |--------------------------------------------------------------------------
            */

            if (
                $question->is_required
                &&
                $this->isEmptyAnswer(
                    $question->question_type,
                    $incoming
                )
            ) {

                throw ValidationException::withMessages([

                    'answers' =>
                        "Please answer the required question: {$question->question_text}",

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Optional + Empty = Skip Validation
            |--------------------------------------------------------------------------
            */

            if (
                $this->isEmptyAnswer(
                    $question->question_type,
                    $incoming
                )
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Answer According To Question Type
            |--------------------------------------------------------------------------
            */

            $this->validateAnswer(
                $question,
                $incoming
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Reject Answers For Questions Outside This Form
        |--------------------------------------------------------------------------
        */

        $validQuestionIds =
            $form
                ->questions
                ->pluck(
                    'evaluation_question_id'
                )
                ->map(
                    fn ($id) =>
                        (int) $id
                )
                ->all();


        foreach (
            array_keys(
                $answerMap
            )
            as $questionId
        ) {

            if (
                !in_array(
                    (int) $questionId,
                    $validQuestionIds,
                    true
                )
            ) {

                throw ValidationException::withMessages([

                    'answers' =>
                        'One or more submitted answers do not belong to this evaluation form.',

                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Save Submission + Answers
        |--------------------------------------------------------------------------
        */

        return DB::transaction(
            function () use (
                $form,
                $user,
                $answerMap
            ) {

                $submission =
                    EvaluationSubmission::create([

                        'evaluation_form_id' =>
                            $form->evaluation_form_id,

                        'student_id' =>
                            $user->student_id,

                        'submitted_at' =>
                            now(),

                    ]);


                foreach (
                    $form->questions
                    as $question
                ) {

                    $incoming =
                        $answerMap[
                            $question->evaluation_question_id
                        ]
                        ?? null;


                    if (
                        $this->isEmptyAnswer(
                            $question->question_type,
                            $incoming
                        )
                    ) {

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create Main Answer Record
                    |--------------------------------------------------------------------------
                    */

                    $answer =
                        EvaluationAnswer::create([

                            'evaluation_submission_id' =>
                                $submission->evaluation_submission_id,

                            'evaluation_question_id' =>
                                $question->evaluation_question_id,

                            'answer_text' =>
                                $this->getTextAnswer(
                                    $question->question_type,
                                    $incoming
                                ),

                            'answer_number' =>
                                $this->getNumericAnswer(
                                    $question->question_type,
                                    $incoming
                                ),

                            'answer_boolean' =>
                                $this->getBooleanAnswer(
                                    $question->question_type,
                                    $incoming
                                ),

                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Save Choice Answers
                    |--------------------------------------------------------------------------
                    */

                    if (
                        in_array(
                            $question->question_type,
                            [
                                'Single Choice',
                                'Multiple Choice',
                            ],
                            true
                        )
                    ) {

                        $optionIds =
                            $incoming['option_ids']
                            ?? [];


                        foreach (
                            $optionIds
                            as $optionId
                        ) {

                            EvaluationAnswerOption::create([

                                'evaluation_answer_id' =>
                                    $answer->evaluation_answer_id,

                                'evaluation_question_option_id' =>
                                    $optionId,

                            ]);
                        }
                    }
                }


                return $submission
                    ->fresh()
                    ->load([
                        'form.event',
                        'answers.question',
                        'answers.selectedOptions.option',
                    ]);

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETERMINE IF ANSWER IS EMPTY
    |--------------------------------------------------------------------------
    */

    private function isEmptyAnswer(
        string $type,
        ?array $answer
    ): bool {

        if (!$answer) {

            return true;
        }


        return match ($type) {

            'Short Answer',
            'Paragraph' =>

                !isset(
                    $answer['value']
                )
                ||
                trim(
                    (string) $answer['value']
                ) === '',


            'Rating' =>

                !isset(
                    $answer['value']
                )
                ||
                $answer['value'] === '',


            'Yes No' =>

                !array_key_exists(
                    'value',
                    $answer
                )
                ||
                $answer['value'] === null
                ||
                $answer['value'] === '',


            'Single Choice',
            'Multiple Choice' =>

                empty(
                    $answer['option_ids']
                ),


            default =>
                true,

        };
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE ANSWER
    |--------------------------------------------------------------------------
    */

    private function validateAnswer(
        $question,
        array $answer
    ): void {

        switch (
            $question->question_type
        ) {

            /*
            |--------------------------------------------------------------------------
            | Text Questions
            |--------------------------------------------------------------------------
            */

            case 'Short Answer':

                if (
                    strlen(
                        (string) (
                            $answer['value']
                            ?? ''
                        )
                    ) > 1000
                ) {

                    throw ValidationException::withMessages([

                        'answers' =>
                            "The answer for \"{$question->question_text}\" is too long.",

                    ]);
                }

                break;


            case 'Paragraph':

                if (
                    strlen(
                        (string) (
                            $answer['value']
                            ?? ''
                        )
                    ) > 5000
                ) {

                    throw ValidationException::withMessages([

                        'answers' =>
                            "The answer for \"{$question->question_text}\" is too long.",

                    ]);
                }

                break;


            /*
            |--------------------------------------------------------------------------
            | Rating
            |--------------------------------------------------------------------------
            */

            case 'Rating':

                $value =
                    filter_var(
                        $answer['value']
                        ?? null,
                        FILTER_VALIDATE_INT
                    );


                if (
                    $value === false
                    ||
                    $value
                    <
                    $question->rating_min
                    ||
                    $value
                    >
                    $question->rating_max
                ) {

                    throw ValidationException::withMessages([

                        'answers' =>
                            "Invalid rating for \"{$question->question_text}\".",

                    ]);
                }

                break;


            /*
            |--------------------------------------------------------------------------
            | Yes / No
            |--------------------------------------------------------------------------
            */

            case 'Yes No':

                $value =
                    $answer['value']
                    ?? null;


                if (
                    !in_array(
                        $value,
                        [
                            true,
                            false,
                            1,
                            0,
                            '1',
                            '0',
                        ],
                        true
                    )
                ) {

                    throw ValidationException::withMessages([

                        'answers' =>
                            "Invalid Yes/No answer for \"{$question->question_text}\".",

                    ]);
                }

                break;


            /*
            |--------------------------------------------------------------------------
            | Choice Questions
            |--------------------------------------------------------------------------
            */

            case 'Single Choice':
            case 'Multiple Choice':

                $optionIds =
                    array_values(
                        array_unique(
                            array_map(
                                'intval',
                                $answer['option_ids']
                                ?? []
                            )
                        )
                    );


                $validOptionIds =
                    $question
                        ->options
                        ->pluck(
                            'evaluation_question_option_id'
                        )
                        ->map(
                            fn ($id) =>
                                (int) $id
                        )
                        ->all();


                foreach (
                    $optionIds
                    as $optionId
                ) {

                    if (
                        !in_array(
                            $optionId,
                            $validOptionIds,
                            true
                        )
                    ) {

                        throw ValidationException::withMessages([

                            'answers' =>
                                "Invalid option selected for \"{$question->question_text}\".",

                        ]);
                    }
                }


                if (
                    $question->question_type
                    === 'Single Choice'
                    &&
                    count(
                        $optionIds
                    ) !== 1
                ) {

                    throw ValidationException::withMessages([

                        'answers' =>
                            "Please select exactly one option for \"{$question->question_text}\".",

                    ]);
                }


                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ANSWER VALUE HELPERS
    |--------------------------------------------------------------------------
    */

    private function getTextAnswer(
        string $type,
        array $answer
    ): ?string {

        if (
            !in_array(
                $type,
                [
                    'Short Answer',
                    'Paragraph',
                ],
                true
            )
        ) {

            return null;
        }


        return trim(
            (string) (
                $answer['value']
                ?? ''
            )
        );
    }


    private function getNumericAnswer(
        string $type,
        array $answer
    ): ?int {

        if (
            $type !== 'Rating'
        ) {

            return null;
        }


        return (int) $answer['value'];
    }


    private function getBooleanAnswer(
        string $type,
        array $answer
    ): ?bool {

        if (
            $type !== 'Yes No'
        ) {

            return null;
        }


        return filter_var(
            $answer['value'],
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        );
    }
}