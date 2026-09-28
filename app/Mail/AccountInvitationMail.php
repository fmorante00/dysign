<?php

namespace App\Mail;

use App\Models\AccountInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountInvitationMail extends Mailable
{
    use Queueable, SerializesModels;


    public $invitation;


    public function __construct(AccountInvitation $invitation)
    {
        $this->invitation = $invitation;
    }



    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to DySign - Complete Your Account Setup',
        );
    }



    public function content(): Content
    {
        return new Content(
            view: 'emails.account-invitation',
        );
    }



    public function attachments(): array
    {
        return [];
    }
}