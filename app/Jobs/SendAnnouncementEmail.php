<?php

namespace App\Jobs;

use App\Mail\StudentAnnouncementMail;
use App\Models\Announcement;
use App\Models\AnnouncementRecipient;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SendAnnouncementEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [
        10,
        30,
        60,
    ];


    public function __construct(
        public int $recipientId
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESS EMAIL
    |--------------------------------------------------------------------------
    */

    public function handle(): void
    {
        $recipient =
            AnnouncementRecipient::with([
                'announcement.event',
                'announcement.attachments',
                'student',
                'user',
            ])
            ->findOrFail(
                $this->recipientId
            );


        $announcement =
            $recipient->announcement;


        /*
        |--------------------------------------------------------------------------
        | Recipient Name
        |--------------------------------------------------------------------------
        */

        $recipientName =
            $this->recipientName(
                $recipient
            );


        /*
        |--------------------------------------------------------------------------
        | Prepare Attachments
        |--------------------------------------------------------------------------
        */

        $mailAttachments =
            $announcement
                ->attachments
                ->map(function ($attachment) {

                    return [

                        'path' =>
                            $attachment->file_path,

                        'name' =>
                            $attachment->file_name,

                        'mime' =>
                            $attachment->mime_type,

                    ];

                })
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | Send Email
        |--------------------------------------------------------------------------
        */

        Mail::to(
            $recipient->email
        )
        ->send(
            new StudentAnnouncementMail(
                $announcement,
                $recipientName,
                $mailAttachments
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Mark Recipient as Sent
        |--------------------------------------------------------------------------
        */

        $recipient->update([

            'status' =>
                'Sent',

            'sent_at' =>
                now(),

            'error_message' =>
                null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Refresh Announcement Status
        |--------------------------------------------------------------------------
        */

        $this->refreshAnnouncementStatus(
            $announcement
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FINAL FAILURE
    |--------------------------------------------------------------------------
    |
    | Runs only after Laravel exhausts all retry attempts.
    |
    */

    public function failed(
        Throwable $exception
    ): void {

        $recipient =
            AnnouncementRecipient::find(
                $this->recipientId
            );


        if (!$recipient) {
            return;
        }


        $recipient->update([

            'status' =>
                'Failed',

            'sent_at' =>
                null,

            'error_message' =>
                Str::limit(
                    $exception->getMessage(),
                    1000
                ),

        ]);


        $announcement =
            Announcement::find(
                $recipient->announcement_id
            );


        if ($announcement) {

            $this->refreshAnnouncementStatus(
                $announcement
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RECIPIENT NAME
    |--------------------------------------------------------------------------
    */

    private function recipientName(
        AnnouncementRecipient $recipient
    ): string {

        if (
            $recipient->recipient_type ===
            'Student'
        ) {

            $student =
                $recipient->student;


            if ($student) {

                return trim(

                    $student->first_name

                    . ' '

                    . (
                        $student->middle_name
                            ? $student->middle_name . ' '
                            : ''
                    )

                    . $student->last_name

                );
            }
        }


        if (
            $recipient->recipient_type ===
            'Personnel'
        ) {

            if ($recipient->user) {

                return $recipient
                    ->user
                    ->name;
            }
        }


        return 'Recipient';
    }


    /*
    |--------------------------------------------------------------------------
    | ANNOUNCEMENT DELIVERY STATUS
    |--------------------------------------------------------------------------
    */

    private function refreshAnnouncementStatus(
        Announcement $announcement
    ): void {

        $total =
            $announcement
                ->recipients()
                ->count();


        $pending =
            $announcement
                ->recipients()
                ->where(
                    'status',
                    'Pending'
                )
                ->count();


        $sent =
            $announcement
                ->recipients()
                ->where(
                    'status',
                    'Sent'
                )
                ->count();


        $failed =
            $announcement
                ->recipients()
                ->where(
                    'status',
                    'Failed'
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Still Processing
        |--------------------------------------------------------------------------
        */

        if ($pending > 0) {

            $announcement->update([

                'status' =>
                    'Processing',

                'sent_at' =>
                    null,

            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | All Sent Successfully
        |--------------------------------------------------------------------------
        */

        if (
            $total > 0 &&
            $sent === $total
        ) {

            $announcement->update([

                'status' =>
                    'Sent',

                'sent_at' =>
                    now(),

            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Some Sent, Some Failed
        |--------------------------------------------------------------------------
        */

        if (
            $sent > 0 &&
            $failed > 0
        ) {

            $announcement->update([

                'status' =>
                    'Partially Sent',

                'sent_at' =>
                    now(),

            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Everything Failed
        |--------------------------------------------------------------------------
        */

        $announcement->update([

            'status' =>
                'Failed',

            'sent_at' =>
                null,

        ]);
    }
}