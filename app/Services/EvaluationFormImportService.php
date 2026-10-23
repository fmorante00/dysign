<?php

namespace App\Services;

use App\Models\EvaluationForm;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationQuestionOption;
use App\Models\Event;
use App\Models\User;

use DOMDocument;
use DOMXPath;
use ZipArchive;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use Throwable;

class EvaluationFormImportService
{
    /*
    |--------------------------------------------------------------------------
    | Supported Word Tags
    |--------------------------------------------------------------------------
    */

    private const QUESTION_TYPES = [

        'SHORT_ANSWER' =>
            'Short Answer',

        'PARAGRAPH' =>
            'Paragraph',

        'SINGLE_CHOICE' =>
            'Single Choice',

        'MULTIPLE_CHOICE' =>
            'Multiple Choice',

        'RATING' =>
            'Rating',

        'YES_NO' =>
            'Yes No',

    ];


    /*
    |--------------------------------------------------------------------------
    | IMPORT WORD EVALUATION FORM
    |--------------------------------------------------------------------------
    */

    public function import(
        User $user,
        Event $event,
        UploadedFile $file,
        ?string $requestedTitle = null,
        ?string $requestedDescription = null
    ): EvaluationForm {

        /*
        |--------------------------------------------------------------------------
        | Department Authorization
        |--------------------------------------------------------------------------
        */

        if (!$user->department_id) {

            abort(
                403,
                'Your account is not assigned to a department.'
            );
        }


        if (
            (int) $event->department_id
            !==
            (int) $user->department_id
        ) {

            abort(
                403,
                'You are not authorized to create an evaluation for this event.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Multiple Active Forms
        |--------------------------------------------------------------------------
        */

        $existing =
            EvaluationForm::query()
                ->where(
                    'event_id',
                    $event->event_id
                )
                ->whereIn(
                    'status',
                    [
                        'Draft',
                        'Scheduled',
                        'Published',
                    ]
                )
                ->exists();


        if ($existing) {

            throw ValidationException::withMessages([

                'event_id' =>
                    'This event already has an active evaluation form.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Parse Word Before Saving
        |--------------------------------------------------------------------------
        */

        $parsed =
            $this->parseDocx(
                $file
            );


        if (
            count(
                $parsed['questions']
            ) === 0
        ) {

            throw ValidationException::withMessages([

                'source_file' =>
                    'No valid evaluation questions were found in the Word document.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Title / Description
        |--------------------------------------------------------------------------
        */

        $title =
            trim(
                $requestedTitle
                ?? ''
            );


        if ($title === '') {

            $title =
                trim(
                    $parsed['title']
                    ?? ''
                );
        }


        if ($title === '') {

            $title =
                $event->event_name
                . ' Evaluation';
        }


        $description =
            trim(
                $requestedDescription
                ?? ''
            );


        if ($description === '') {

            $description =
                $parsed['description']
                ?? null;
        }


        /*
        |--------------------------------------------------------------------------
        | Store Original Word File Privately
        |--------------------------------------------------------------------------
        */

        $storedPath =
            $file->store(
                'evaluation_form_sources',
                'local'
            );


        try {

            $evaluationForm =
                DB::transaction(
                    function () use (
                        $user,
                        $event,
                        $file,
                        $storedPath,
                        $title,
                        $description,
                        $parsed
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Create Form
                        |--------------------------------------------------------------------------
                        */

                        $form =
                            EvaluationForm::create([

                                'event_id' =>
                                    $event->event_id,

                                'created_by' =>
                                    $user->user_id,

                                'title' =>
                                    $title,

                                'description' =>
                                    $description,

                                'source_file_name' =>
                                    $file->getClientOriginalName(),

                                'source_file_path' =>
                                    $storedPath,

                                'status' =>
                                    'Scheduled',

                            ]);


                        /*
                        |--------------------------------------------------------------------------
                        | Create Questions
                        |--------------------------------------------------------------------------
                        */

                        foreach (
                            $parsed['questions']
                            as $index => $questionData
                        ) {

                            $question =
                                EvaluationQuestion::create([

                                    'evaluation_form_id' =>
                                        $form->evaluation_form_id,

                                    'question_text' =>
                                        $questionData['question_text'],

                                    'question_type' =>
                                        $questionData['question_type'],

                                    'is_required' =>
                                        $questionData['is_required'],

                                    'rating_min' =>
                                        $questionData['rating_min'],

                                    'rating_max' =>
                                        $questionData['rating_max'],

                                    'rating_min_label' =>
                                        $questionData['rating_min_label'],

                                    'rating_max_label' =>
                                        $questionData['rating_max_label'],

                                    'sort_order' =>
                                        $index + 1,

                                ]);


                            /*
                            |--------------------------------------------------------------------------
                            | Create Options
                            |--------------------------------------------------------------------------
                            */

                            foreach (
                                $questionData['options']
                                as $optionIndex => $optionText
                            ) {

                                EvaluationQuestionOption::create([

                                    'evaluation_question_id' =>
                                        $question->evaluation_question_id,

                                    'option_text' =>
                                        $optionText,

                                    'sort_order' =>
                                        $optionIndex + 1,

                                ]);
                            }
                        }


                        return $form;

                    }
                );

        }
        catch (Throwable $exception) {

            Storage::disk(
                'local'
            )
            ->delete(
                $storedPath
            );


            throw $exception;
        }


        return $evaluationForm
            ->load([
                'event',
                'creator',
                'questions.options',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PARSE DOCX
    |--------------------------------------------------------------------------
    */

    private function parseDocx(
        UploadedFile $file
    ): array {

        $zip =
            new ZipArchive();


        $opened =
            $zip->open(
                $file->getRealPath()
            );


        if ($opened !== true) {

            throw ValidationException::withMessages([

                'source_file' =>
                    'The uploaded Word document could not be opened.',

            ]);
        }


        $xml =
            $zip->getFromName(
                'word/document.xml'
            );


        $zip->close();


        if ($xml === false) {

            throw ValidationException::withMessages([

                'source_file' =>
                    'The uploaded file is not a valid Word .docx document.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Extract Word Paragraphs
        |--------------------------------------------------------------------------
        */

        $lines =
            $this->extractParagraphs(
                $xml
            );


        return $this->parseLines(
            $lines
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXTRACT PARAGRAPHS FROM WORD XML
    |--------------------------------------------------------------------------
    */

    private function extractParagraphs(
        string $xml
    ): array {

        $dom =
            new DOMDocument();


        libxml_use_internal_errors(
            true
        );


        $loaded =
            $dom->loadXML(
                $xml
            );


        libxml_clear_errors();


        if (!$loaded) {

            throw ValidationException::withMessages([

                'source_file' =>
                    'Unable to read the contents of the Word document.',

            ]);
        }


        $xpath =
            new DOMXPath(
                $dom
            );


        $xpath->registerNamespace(
            'w',
            'http://schemas.openxmlformats.org/wordprocessingml/2006/main'
        );


        $paragraphs =
            $xpath->query(
                '//w:p'
            );


        $lines = [];


        foreach (
            $paragraphs
            as $paragraph
        ) {

            $textNodes =
                $xpath->query(
                    './/w:t',
                    $paragraph
                );


            $text = '';


            foreach (
                $textNodes
                as $textNode
            ) {

                $text .=
                    $textNode->textContent;
            }


            $text =
                trim(
                    preg_replace(
                        '/\s+/u',
                        ' ',
                        $text
                    )
                );


            if ($text !== '') {

                $lines[] =
                    $text;
            }
        }


        return $lines;
    }


    /*
    |--------------------------------------------------------------------------
    | PARSE DOCUMENT LINES
    |--------------------------------------------------------------------------
    */

    private function parseLines(
        array $lines
    ): array {

        $result = [

            'title' =>
                null,

            'description' =>
                null,

            'questions' =>
                [],

        ];


        $current =
            null;


        $readingOptions =
            false;


        foreach (
            $lines
            as $line
        ) {

            /*
            |--------------------------------------------------------------------------
            | Form Title
            |--------------------------------------------------------------------------
            */

            if (
                $current === null
                &&
                str_starts_with(
                    strtoupper($line),
                    'TITLE:'
                )
            ) {

                $result['title'] =
                    trim(
                        substr(
                            $line,
                            strlen('TITLE:')
                        )
                    );


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Form Description
            |--------------------------------------------------------------------------
            */

            if (
                $current === null
                &&
                str_starts_with(
                    strtoupper($line),
                    'DESCRIPTION:'
                )
            ) {

                $result['description'] =
                    trim(
                        substr(
                            $line,
                            strlen('DESCRIPTION:')
                        )
                    );


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Question Type Tag
            |--------------------------------------------------------------------------
            */

            if (
                preg_match(
                    '/^\[([A-Z_]+)\]$/',
                    strtoupper(
                        $line
                    ),
                    $matches
                )
            ) {

                if (
                    $current !== null
                ) {

                    $result['questions'][] =
                        $this->finalizeQuestion(
                            $current
                        );
                }


                $tag =
                    $matches[1];


                if (
                    !array_key_exists(
                        $tag,
                        self::QUESTION_TYPES
                    )
                ) {

                    throw ValidationException::withMessages([

                        'source_file' =>
                            "Unsupported question type [{$tag}].",

                    ]);
                }


                $current = [

                    'question_type' =>
                        self::QUESTION_TYPES[$tag],

                    'question_text' =>
                        null,

                    'is_required' =>
                        false,

                    'rating_min' =>
                        null,

                    'rating_max' =>
                        null,

                    'rating_min_label' =>
                        null,

                    'rating_max_label' =>
                        null,

                    'options' =>
                        [],

                ];


                $readingOptions =
                    false;


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Ignore Lines Before First Question
            |--------------------------------------------------------------------------
            */

            if (
                $current === null
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Required
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with(
                    strtoupper($line),
                    'REQUIRED:'
                )
            ) {

                $value =
                    strtolower(
                        trim(
                            substr(
                                $line,
                                strlen('Required:')
                            )
                        )
                    );


                $current['is_required'] =
                    in_array(
                        $value,
                        [
                            'yes',
                            'true',
                            '1',
                        ],
                        true
                    );


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Rating Scale
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with(
                    strtoupper($line),
                    'SCALE:'
                )
            ) {

                $scale =
                    trim(
                        substr(
                            $line,
                            strlen('Scale:')
                        )
                    );


                if (
                    !preg_match(
                        '/^(\d+)\s*-\s*(\d+)$/',
                        $scale,
                        $matches
                    )
                ) {

                    throw ValidationException::withMessages([

                        'source_file' =>
                            "Invalid rating scale: {$scale}. Use format such as 1-5.",

                    ]);
                }


                $current['rating_min'] =
                    (int) $matches[1];


                $current['rating_max'] =
                    (int) $matches[2];


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Rating Minimum Label
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with(
                    strtoupper($line),
                    'MIN LABEL:'
                )
            ) {

                $current['rating_min_label'] =
                    trim(
                        substr(
                            $line,
                            strlen('Min Label:')
                        )
                    );


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Rating Maximum Label
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with(
                    strtoupper($line),
                    'MAX LABEL:'
                )
            ) {

                $current['rating_max_label'] =
                    trim(
                        substr(
                            $line,
                            strlen('Max Label:')
                        )
                    );


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Options Marker
            |--------------------------------------------------------------------------
            */

            if (
                strtoupper(
                    $line
                )
                === 'OPTIONS:'
            ) {

                $readingOptions =
                    true;


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | First Normal Line = Question Text
            |--------------------------------------------------------------------------
            */

            if (
                $current['question_text']
                === null
            ) {

                $current['question_text'] =
                    $line;


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Option Lines
            |--------------------------------------------------------------------------
            */

            if (
                $readingOptions
            ) {

                $current['options'][] =
                    $line;


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Unknown Extra Line
            |--------------------------------------------------------------------------
            */

            throw ValidationException::withMessages([

                'source_file' =>
                    "Unrecognized line near question \"{$current['question_text']}\": {$line}",

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Final Question
        |--------------------------------------------------------------------------
        */

        if (
            $current !== null
        ) {

            $result['questions'][] =
                $this->finalizeQuestion(
                    $current
                );
        }


        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PARSED QUESTION
    |--------------------------------------------------------------------------
    */

    private function finalizeQuestion(
        array $question
    ): array {

        if (
            empty(
                $question['question_text']
            )
        ) {

            throw ValidationException::withMessages([

                'source_file' =>
                    'A question type tag was found without question text.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Choice Questions
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $question['question_type'],
                [
                    'Single Choice',
                    'Multiple Choice',
                ],
                true
            )
            &&
            count(
                $question['options']
            ) < 2
        ) {

            throw ValidationException::withMessages([

                'source_file' =>
                    "Question \"{$question['question_text']}\" must contain at least two options.",

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Rating Questions
        |--------------------------------------------------------------------------
        */

        if (
            $question['question_type']
            === 'Rating'
        ) {

            if (
                $question['rating_min']
                === null
                ||
                $question['rating_max']
                === null
            ) {

                throw ValidationException::withMessages([

                    'source_file' =>
                        "Rating question \"{$question['question_text']}\" must include a Scale such as 1-5.",

                ]);
            }


            if (
                $question['rating_min']
                >=
                $question['rating_max']
            ) {

                throw ValidationException::withMessages([

                    'source_file' =>
                        "Rating question \"{$question['question_text']}\" has an invalid scale.",

                ]);
            }


            if (
                $question['rating_max']
                > 10
            ) {

                throw ValidationException::withMessages([

                    'source_file' =>
                        'Rating scales cannot exceed 10.',

                ]);
            }
        }


        return $question;
    }
}