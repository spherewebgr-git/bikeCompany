<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Η παραγγελία που θα χρησιμοποιηθεί μέσα στο email.
     */
    public function __construct(
        public Order $order
    ) {
        //
    }

    /**
     * Το subject του email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Bike Company Order Has Been Completed',
        );
    }

    /**
     * Το Blade template που θα χρησιμοποιηθεί
     * ως περιεχόμενο του email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-completed',
        );
    }

    /**
     * Αρχεία που θα επισυνάπτονται στο email.
     */
    public function attachments(): array
    {
        return [];
    }
}
