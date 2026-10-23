<?php

namespace App\Services;

use App\Models\Event;
use App\Models\ExcuseRequest;
use App\Models\ExcuseRequestDocument;
use App\Models\User;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use Throwable;

class ExcuseRequestService
{
    /*
    |--------------------------------------------------------------------------
    | SUBMIT EXCUSE REQUEST
    |--------------------------------------------------------------------------
    */

    public function submit(
        User $user,
        array $data,
        array $files
    ): ExcuseRequest {

        /*
        |--------------------------------------------------------------------------
        | Student Account Validation
        |--------------------------------------------------------------------------
        */

        if (!$user->student_id) {

            throw ValidationException::withMessages([

                'student' =>
                    'No student record is linked to this account.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        $event =
            Event::findOrFail(
                $data['event_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Check Event Eligibility
        |--------------------------------------------------------------------------
        |
        | If the event already has generated eligible participants,
        | the current student must belong to that list.
        |
        */

        $eventHasEligibility =
            DB::table(
                'event_eligible_students'
            )
            ->where(
                'event_id',
                $event->event_id
            )
            ->exists();


        if ($eventHasEligibility) {

            $studentIsEligible =
                DB::table(
                    'event_eligible_students'
                )
                ->where(
                    'event_id',
                    $event->event_id
                )
                ->where(
                    'student_id',
                    $user->student_id
                )
                ->exists();


            if (!$studentIsEligible) {

                throw ValidationException::withMessages([

                    'event_id' =>
                        'You are not an eligible participant for this event.',

                ]);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Request
        |--------------------------------------------------------------------------
        */

        $duplicate =
            ExcuseRequest::query()
                ->where(
                    'event_id',
                    $event->event_id
                )
                ->where(
                    'student_id',
                    $user->student_id
                )
                ->exists();


        if ($duplicate) {

            throw ValidationException::withMessages([

                'event_id' =>
                    'You have already submitted an excuse request for this event.',

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Automatic Request Timing
        |--------------------------------------------------------------------------
        |
        | Student does NOT manually select this.
        |
        | Before event starts:
        | Pre-Event
        |
        | Event already started / happened:
        | Post-Event
        |
        */

        $eventDate =
            Carbon::parse(
                $event->event_date
            )
            ->format(
                'Y-m-d'
            );


        $eventStartTime =
            Carbon::parse(
                $event->start_time
            )
            ->format(
                'H:i:s'
            );


        $eventStartsAt =
            Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $eventDate
                . ' '
                . $eventStartTime,
                'Asia/Manila'
            );


        $now =
            Carbon::now(
                'Asia/Manila'
            );


        $requestTiming =
            $now->lt(
                $eventStartsAt
            )
                ? 'Pre-Event'
                : 'Post-Event';


        /*
        |--------------------------------------------------------------------------
        | Store Request + Documents
        |--------------------------------------------------------------------------
        */

        $storedFiles = [];


        try {

            $excuseRequest =
                DB::transaction(
                    function () use (
                        $event,
                        $user,
                        $data,
                        $files,
                        $requestTiming,
                        &$storedFiles
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Excuse Request
                        |--------------------------------------------------------------------------
                        */

                        $excuseRequest =
                            ExcuseRequest::create([

                                'event_id' =>
                                    $event->event_id,

                                'student_id' =>
                                    $user->student_id,

                                'request_timing' =>
                                    $requestTiming,

                                'reason' =>
                                    $data['reason'],

                                'status' =>
                                    'Pending',

                                'submitted_at' =>
                                    now(),

                            ]);


                        /*
                        |--------------------------------------------------------------------------
                        | Supporting Documents
                        |--------------------------------------------------------------------------
                        */

                        foreach (
                            $files
                            as $file
                        ) {

                            $path =
                                $file->store(
                                    'excuse_request_documents',
                                    'local'
                                );


                            $storedFiles[] =
                                $path;


                            ExcuseRequestDocument::create([

                                'excuse_request_id' =>
                                    $excuseRequest
                                        ->excuse_request_id,

                                'file_name' =>
                                    $file
                                        ->getClientOriginalName(),

                                'file_path' =>
                                    $path,

                                'mime_type' =>
                                    $file
                                        ->getMimeType(),

                                'file_size' =>
                                    $file
                                        ->getSize(),

                            ]);

                        }


                        return $excuseRequest;

                    }
                );

        }
        catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Delete Stored Files If DB Saving Fails
            |--------------------------------------------------------------------------
            */

            foreach (
                $storedFiles
                as $path
            ) {

                Storage::disk(
                    'local'
                )
                ->delete(
                    $path
                );

            }


            throw $exception;
        }


        /*
        |--------------------------------------------------------------------------
        | Return Complete Record
        |--------------------------------------------------------------------------
        */

        return $excuseRequest
            ->load([
                'event',
                'student',
                'documents',
            ]);
    }
}