<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class StudentAnnouncementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Announcement $announcement,
        public string $recipientName,
        public array $files = []
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->announcement->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student-announcement',
            with: [
                'announcement' => $this->announcement,
                'recipientName' => $this->recipientName,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->files as $file) {

            if (empty($file['path'])) {
                continue;
            }

            if (!Storage::disk('local')->exists($file['path'])) {
                continue;
            }

            $attachment = Attachment::fromPath(
                Storage::disk('local')->path($file['path'])
            );

            if (!empty($file['name'])) {
                $attachment = $attachment->as(
                    $file['name']
                );
            }

            if (!empty($file['mime'])) {
                $attachment = $attachment->withMime(
                    $file['mime']
                );
            }

            $attachments[] = $attachment;
        }

        return $attachments;
    }
}