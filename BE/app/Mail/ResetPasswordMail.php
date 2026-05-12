<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;

    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mã xác nhận khôi phục mật khẩu - Heritage Archivist',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reset_password',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
