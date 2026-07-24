<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Location;
use App\Models\Order;
use App\Models\Price;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{

    //------------------------- SALE -----------------------------------//
    public function createSale(Bike $bike)
    {

        if ($bike->quantity <= 0) {
            return redirect()
                ->back()
                ->with('error', 'This bike is currently out of stock.');
        }

        $user = auth()->user();

        return view('checkout.sale.create', compact(
            'bike',
            'user'
        ));
    }

    public function storeSale(Request $request, Bike $bike)
    {
        $validated = $request->validate([
            'dropoff_address' => ['required', 'string', 'max:255'],
        ]);

        try {
            $order = DB::transaction(function () use ($bike, $validated) {

                /*
                 * Ξαναδιαβάζουμε το ποδήλατο από τη βάση
                 * και κλειδώνουμε προσωρινά τη γραμμή.
                 */
                $lockedBike = Bike::query()
                    ->whereKey($bike->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * Έλεγχος διαθεσιμότητας.
                 */
                if ($lockedBike->quantity <= 0) {
                    throw ValidationException::withMessages([
                        'bike' => 'This bike is currently unavailable.',
                    ]);
                }

                /*
                 * Παίρνουμε την τιμή πώλησης.
                 */
                $price = $lockedBike->prices()->first()?->price;

                if ($price === null) {
                    throw ValidationException::withMessages([
                        'bike' => 'No sale price has been configured for this bike.',
                    ]);
                }

                /*
                 * Παίρνουμε το αρχικό status.
                 */
                $statusId = Status::where('step', 0)->value('id');

                if (!$statusId) {
                    throw ValidationException::withMessages([
                        'order' => 'The initial order status was not found.',
                    ]);
                }

                /*
                 * Δημιουργούμε την προσωρινή παραγγελία.
                 */
                $order = Order::create([
                    'price' => $price,
                    'order_date' => now(),
                    'payed_off' => false,
                    'dropoff_address' => $validated['dropoff_address'],
                    'bike_id' => $lockedBike->id,
                    'user_id' => auth()->id(),
                    'status_id' => $statusId,
                    'reserved_until' => now()->addMinutes(2),
                    'completed_at' => null,
                    'card_id' => null,
                ]);

                /*
                 * Μειώνουμε το διαθέσιμο απόθεμα.
                 */
                $lockedBike->decrement('quantity');

                return $order;
            });
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()->route('payment.index', [
            'order' => $order->id,
        ]);
    }


    //------------------------- RENTAL -----------------------------------//
    public function createRental(Request $request, Bike $bike)
    {
        $locations = Location::all();

        // defaults: ξεκινάει από τώρα, 1 ώρα διάρκεια — ο χρήστης τα αλλάζει στο checkout
        $rentStart = now();
        $duration  = 1;
        $rentEnd   = $rentStart->copy()->addHours($duration);


        $hourPrice = $bike->prices[0]?->price ?? 0;
        $price     = $duration * $hourPrice;

        return view('checkout.rental.create', [
            'bike'      => $bike,
            'user'      => auth()->user(),
            'locations' => $locations,
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
            'location_id' => ['required', 'exists:locations,id'],
            'rent_start'  => 'required|date',
            'rent_end'    => 'required|date|after:rent_start',
            'rental_type' => 'required|in:hour,day,week',
        ]);

        $start = Carbon::parse($validated['rent_start']);
        $end   = Carbon::parse($validated['rent_end']);
        $type  = $validated['rental_type'];

        // Το όριο 72 ωρών έχει νόημα μόνο στο hour mode.
        // Days/weeks δεν έχουν κανένα περιορισμό, όπως ζητήθηκε.
        if ($type === 'hour' && $start->diffInHours($end) > 72) {
            return back()->withErrors([
                'rent_end' => 'Η μέγιστη διάρκεια ενοικίασης σε ώρες είναι 72.',
            ]);
        }

        try {
            $order = DB::transaction(function () use ($bike, $start, $end, $type, $validated) {

                $lockedBike = Bike::query()
                    ->whereKey($bike->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$lockedBike->isAvailable($start, $end)) {
                    throw ValidationException::withMessages([
                        'rent_start' => 'Το ποδήλατο δεν είναι διαθέσιμο αυτή την περίοδο.',
                    ]);
                }

                $priceIndex = match ($type) {
                    'hour' => 0,
                    'day'  => 1,
                    'week' => 2,
                    default => 0,
                };

                $pricePerUnit = $lockedBike->prices[$priceIndex]?->price
                    ?? $lockedBike->prices[0]?->price
                    ?? 0;

                $hours = $start->diffInHours($end);
                $days  = $start->diffInDays($end);

                $units = match ($type) {
                    'week' => max(1, (int) ceil($days / 7)),
                    'day'  => max(1, $days),
                    'hour' => max(1, $hours),
                };

                $price = $units * $pricePerUnit;

                $statusId = Status::where('step', 0)->value('id');

                if (!$statusId) {
                    throw ValidationException::withMessages([
                        'order' => 'The initial order status was not found.',
                    ]);
                }

                return Order::create([
                    'bike_id'        => $lockedBike->id,
                    'user_id'        => auth()->id(),
                    'location_id'    => $validated['location_id'],
                    'price'          => $price,
                    'order_date'     => now(),
                    'payed_off'      => false,
                    'reserved_until' => now()->addMinutes(15),
                    'completed_at'   => null,
                    'rent_start'     => $start,
                    'rent_end'       => $end,
                    'status_id'      => $statusId,
                ]);
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return redirect()->route('payment.index', $order);
    }
}
