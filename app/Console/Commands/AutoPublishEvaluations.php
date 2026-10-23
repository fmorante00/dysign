<?php

namespace App\Console\Commands;

use App\Models\EvaluationForm;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoPublishEvaluations extends Command
{
    /*
    |--------------------------------------------------------------------------
    | Command
    |--------------------------------------------------------------------------
    */

    protected $signature =
        'evaluations:auto-publish';


    protected $description =
        'Automatically publish scheduled evaluation forms after their events end.';


    /*
    |--------------------------------------------------------------------------
    | Execute Command
    |--------------------------------------------------------------------------
    */

    public function handle(): int
    {
        $now =
            Carbon::now(
                'Asia/Manila'
            );


        $forms =
            EvaluationForm::query()
                ->with(
                    'event'
                )
                ->where(
                    'status',
                    'Scheduled'
                )
                ->get();


        $publishedCount =
            0;


        foreach (
            $forms
            as $form
        ) {

            $event =
                $form->event;


            /*
            |--------------------------------------------------------------------------
            | Skip Invalid Event Data
            |--------------------------------------------------------------------------
            */

            if (
                !$event
                ||
                !$event->event_date
                ||
                !$event->end_time
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize Event Date
            |--------------------------------------------------------------------------
            */

            $eventDate =
                Carbon::parse(
                    $event->event_date
                )
                ->format(
                    'Y-m-d'
                );


            /*
            |--------------------------------------------------------------------------
            | Normalize Event End Time
            |--------------------------------------------------------------------------
            */

            $eventEndTime =
                Carbon::parse(
                    $event->end_time
                )
                ->format(
                    'H:i:s'
                );


            /*
            |--------------------------------------------------------------------------
            | Build Complete Event End Date/Time
            |--------------------------------------------------------------------------
            */

            $eventEndsAt =
                Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $eventDate
                    . ' '
                    . $eventEndTime,
                    'Asia/Manila'
                );


            /*
            |--------------------------------------------------------------------------
            | Event Has Not Ended Yet
            |--------------------------------------------------------------------------
            */

            if (
                $now->lt(
                    $eventEndsAt
                )
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Automatically Publish Evaluation
            |--------------------------------------------------------------------------
            */

            $form->update([

                'status' =>
                    'Published',

                'published_at' =>
                    now(),

                'closed_at' =>
                    null,

            ]);


            $publishedCount++;


            Log::info(
                'Evaluation form automatically published.',
                [
                    'evaluation_form_id' =>
                        $form->evaluation_form_id,

                    'event_id' =>
                        $form->event_id,

                    'published_at' =>
                        now()->toDateTimeString(),
                ]
            );


            $this->info(
                "Published evaluation form #{$form->evaluation_form_id}: {$form->title}"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Command Summary
        |--------------------------------------------------------------------------
        */

        if (
            $publishedCount === 0
        ) {

            $this->info(
                'No scheduled evaluations are ready for publication.'
            );

        }
        else {

            $this->info(
                "{$publishedCount} evaluation form(s) automatically published."
            );

        }


        return self::SUCCESS;
    }
}