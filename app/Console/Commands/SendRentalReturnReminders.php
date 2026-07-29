<?php

namespace App\Console\Commands;

use App\Mail\RentalReturnReminderMail;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRentalReturnReminders extends Command
{
    protected $signature = 'rentals:send-return-reminders';

    protected $description =
        'Send one reminder one day before a rented bike must be returned';

    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $orders = Order::query()
            ->with([
                'user',
                'location',
                'bike.provision',
                'bike.brand',
                'bike.type',
                'bike.speed',
            ])
            ->whereDate('orders.rent_end', $tomorrow)
            ->where('orders.returned', false)
            ->where('orders.status_id', 5)
            ->whereNull('orders.return_reminder_sent_at')
            ->whereHas('bike.provision', function ($query) {
                $query->whereRaw('LOWER(name) = ?', ['rent']);
            })
            ->whereHas('user', function ($query) {
                $query->whereNotNull('email');
            })
            ->get();

        foreach ($orders as $order) {
            Mail::to($order->user->email)
                ->queue(new RentalReturnReminderMail($order));

            $order->update([
                'return_reminder_sent_at' => now(),
            ]);

            $this->info(
                "Reminder queued for order #{$order->id}: {$order->user->email}"
            );
        }

        $this->info("Total reminders queued: {$orders->count()}");

        return self::SUCCESS;
    }
}
