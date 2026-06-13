<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $senderName;
    public string $senderEmail;
    public string $mailSubject;
    public string $body;

    public function __construct(
        string $senderName,
        string $senderEmail,
        string $mailSubject,
        string $body,
    ) {
        $this->senderName  = $senderName;
        $this->senderEmail = $senderEmail;
        $this->mailSubject = $mailSubject;
        $this->body        = $body;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->senderEmail],
            subject: '[Portfolio Contact] ' . $this->mailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
        );
    }
}
