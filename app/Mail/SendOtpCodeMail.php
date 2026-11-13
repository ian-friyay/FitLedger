<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $userName = 'User',
        public string $actionType = 'Sign In'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your FitLedger {$this->actionType} Verification Code: {$this->otpCode}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth-otp'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
