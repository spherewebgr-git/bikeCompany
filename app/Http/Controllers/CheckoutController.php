<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function create(Bike $bike)
    {
        $user = auth()->user();

        return view('checkout.create', compact(
            'bike',
            'user'
        ));
    }

    public function store(Request $request, Bike $bike)
    {
        $validated = $request->validate([
            'dropoff_address' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $activeReservation = Order::where('bike_id', $bike->id)
            ->whereNull('completed_at')
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '>', now())
            ->exists();

        if ($activeReservation) {
            return redirect()
                ->back()
                ->with('error', 'This bike is currently reserved by another customer.');
        }

        $order = Order::create([
            'price' => $bike->prices->first()->price,
            'order_date' => now(),
            'payed_off' => false,
            'dropoff_address' => $validated['dropoff_address'],
            'bike_id' => $bike->id,
            'user_id' => auth()->id(),
            'status_id' => 1,
            'reserved_until' => now()->addMinutes(15),
            'completed_at' => null,
            'location_id' => null,
            'card_id' => null,
        ]);

        return redirect()->route('payment.index', [
            'order' => $order->id,
        ]);

    }
}
