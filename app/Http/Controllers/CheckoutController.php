<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Order;
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
            'dropoff_address' => [
                'required',
                'string',
                'max:255',
            ],
        ]);


//        $activeReservation = Order::where('bike_id', $bike->id)
//            ->whereNull('completed_at')
//            ->whereNotNull('reserved_until')
//            ->where('reserved_until', '>', now())
//            ->exists();
//
//        if ($activeReservation) {
//
//            return redirect()->back()->with('error', 'This bike is currently reserved by another customer.');
//        }



        $order = DB::transaction(function () use ($bike, $validated) {

            /*
             * Ξαναδιαβάζουμε το ποδήλατο από τη βάση και κλειδώνουμε
             * προσωρινά τη συγκεκριμένη γραμμή.
             *
             * Έτσι, αν δύο χρήστες πατήσουν checkout ταυτόχρονα,
             * δεν θα μειωθεί λάθος το quantity.
             */
            $lockedBike = Bike::query()
                ->whereKey($bike->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
             * Αν δεν υπάρχει διαθέσιμο απόθεμα,
             * δεν δημιουργείται παραγγελία.
             */
            if ($lockedBike->quantity <= 0) {
                throw ValidationException::withMessages([
                    'bike' => 'This bike is currently unavailable.',
                ]);
            }

            /*
             * Παίρνουμε την τιμή πώλησης.
             */
            $price = $lockedBike->prices()
                ->first()
                ?->numeric_price;

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
             * Δημιουργείται η προσωρινή παραγγελία
             * με κράτηση 15 λεπτών.
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
                'location_id' => null,
                'card_id' => null,
            ]);

            /*
             * Το προϊόν δεσμεύεται αμέσως.
             */
            $lockedBike->decrement('quantity');

            return $order;
        });



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
