<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $nickname,
        public readonly string $newPassword,
        public readonly string $siteHost,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Hier kommt dein neues Passwort');
    }

    public function content(): Content
    {
        return new Content(text: 'emails.password-reset');
    }
}
