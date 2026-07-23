<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Order;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    //------------------------- SALE -----------------------------------//
    public function createSale(Bike $bike)
    {
        $user = auth()->user();

        return view('checkout.sale.create', compact(
            'bike',
            'user'
        ));
    }

    public function storeSale(Request $request, Bike $bike)
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

            return redirect()->back()->with('error', 'This bike is currently reserved by another customer.');
        }



        $order = Order::create([
            'price' => $bike->prices->first()->numeric_price,
            'order_date' => now(),
            'payed_off' => false,
            'dropoff_address' => $validated['dropoff_address'],
            'bike_id' => $bike->id,
            'user_id' => auth()->id(),
            'status_id' => Status::where('step', 0)->first()->id,
            'reserved_until' => now()->addMinutes(15),
            'completed_at' => null,
            'location_id' => null,
            'card_id' => null,
        ]);


        return redirect()->route('payment.index', $order);

    }


    //------------------------- RENTAL -----------------------------------//
    public function createRental(Request $request, Bike $bike)
    {
        // defaults: ξεκινάει από τώρα, 1 ώρα διάρκεια — ο χρήστης τα αλλάζει στο checkout
        $rentStart = now();
        $duration  = 1;
        $rentEnd   = $rentStart->copy()->addHours($duration);

        $hourPrice = $bike->prices[0]->numeric_price ?? 0;
        $price     = $duration * $hourPrice;

        return view('checkout.rental.create', [
            'bike'      => $bike,
            'user'      => auth()->user(),
            'rentStart' => $rentStart,
            'rentEnd'   => $rentEnd,
            'duration'  => $duration,
            'hourPrice' => $hourPrice,
            'price'     => $price,
        ]);
    }

    public function storeRental(Request $request, Bike $bike)
    {
        $validated = $request->validate([
            'rent_start' => 'required|date|after_or_equal:now',
            'rent_end'   => 'required|date|after:rent_start',
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

        $start = Carbon::parse($validated['rent_start']);
        $end   = Carbon::parse($validated['rent_end']);

        // ίδιο όριο (72 ώρες) με τη φόρμα του πρώτου βήματος
        if ($start->diffInHours($end) > 72) {
            return back()->withErrors(['rent_end' => 'Η μέγιστη διάρκεια ενοικίασης είναι 72 ώρες.']);
        }

        if (!$bike->isAvailable($start, $end)) {
            return back()->withErrors(['rent_start' => 'Το ποδήλατο δεν είναι διαθέσιμο αυτή την περίοδο.']);
        }

        $type = $request->input('rental_type');

        $priceIndex = match ($type) {
            'hour' => 0,
            'day'  => 1,
            'week' => 2,
            default => 0,
        };

        $priceRow = $bike->prices[$priceIndex] ?? $bike->prices[0];
        $hours = $start->diffInHours($end);
        $days  = $start->diffInDays($end);

        $units = match ($type) {
            'week' => max(1, ceil($days / 7)),
            'day'  => max(1, $days),
            default => max(1, $hours),
        };

        $price = $units * $priceRow->numeric_price;

        $order = Order::create([
            'bike_id'    => $bike->id,
            'user_id'    => auth()->id(),
            'price'      => $price,
            'order_date' => now(),
            'payed_off'  => false,
            'reserved_until' => now()->addMinutes(15),
            'completed_at' => null,
            'rent_start' => $start,
            'rent_end'   => $end,
            'status_id'  => Status::where('step', 0)->first()->id,
        ]);

        return redirect()->route('payment.index', $order);
    }
}
