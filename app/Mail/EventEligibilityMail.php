<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventEligibilityMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientName,
        public string $eventName,
        public string $eventDate,
        public string $startTime,
        public string $endTime,
        public string $venue,
        public string $participationType,
        public string $portalUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'DySign Event Eligibility: '.$this->eventName);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.event-eligibility');
    }

    public function attachments(): array
    {
        return [];
    }
}
