<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends \Illuminate\Mail\Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $contactSubject,
        public string $contactMessage
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Message: ' . $this->contactSubject,
            replyTo: [
                $this->senderEmail,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-us',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
