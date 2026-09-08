<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $nickname,
        public readonly string $activationUrl,
        public readonly string $siteHost,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Account-Aktivierung');
    }

    public function content(): Content
    {
        return new Content(text: 'emails.account-activation');
    }
}
