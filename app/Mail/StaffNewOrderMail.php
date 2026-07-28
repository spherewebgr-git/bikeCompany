<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffNewOrderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Η νέα παραγγελία.
     */
    public function __construct(
        public Order $order
    ) {
        //
    }

    /**
     * Το θέμα του email αλλάζει ανάλογα
     * με το αν είναι αγορά ή ενοικίαση.
     */
    public function envelope(): Envelope
    {
        $isRent =
            strtolower($order->bike->provision->name ?? '') === 'rent';

        return new Envelope(
            subject: $isRent
                ? 'New Bike Rental Order #' . $this->order->id
                : 'New Bike Purchase Order #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-new-order',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
