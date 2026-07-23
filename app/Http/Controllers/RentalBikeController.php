<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Status;
use App\Models\Type;
use App\Models\Speed;
use Illuminate\Http\Request;

class RentalBikeController extends Controller
{
    public function index(Request $request)
    {
        $query = Bike::whereHas('provision', function ($q) {
            $q->where('name', 'rent');
        });

        // Brand
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Type
        if ($request->filled('type')) {
            $query->where('type_id', $request->type);
        }

        // Speed
        if ($request->filled('speed')) {
            $query->where('speed_id', $request->speed);
        }

        // Colour
        if ($request->filled('color')) {
            $query->where('colour', $request->color);
        }

        $bikes = $query->paginate(12)->withQueryString();

        // Για τα dropdowns
        $brands = Brand::orderBy('name')->get();
        $types = Type::orderBy('name')->get();
        $speeds = Speed::orderBy('gears')->get();

        // Μοναδικά χρώματα
        $colors = Bike::select('colour')
            ->distinct()
            ->orderBy('colour')
            ->pluck('colour');

        return view('bikes.rental.index', compact(
            'bikes',
            'brands',
            'types',
            'speeds',
            'colors'
        ));
    }

    public function show(Bike $bike)
    {
        return view('bikes.rental.show', compact('bike'));
    }

    public function availability(Bike $bike)
    {

        $orders = Order::where('bike_id', $bike->id)
            ->whereNotNull('rent_start')
            ->whereNotNull('rent_end')
            ->whereNull('completed_at')
            // ίδιο κριτήριο με Bike::isAvailable(): μόνο πληρωμένες κρατήσεις
            // ή κρατήσεις με ακόμα ενεργό hold μπλοκάρουν πραγματικά μέρες.
            // Ένα order που έμεινε unpaid μετά τη λήξη του reserved_until
            // δεν πρέπει να εμφανίζεται σαν "Not Available" για πάντα.
            ->where(function ($q) {

                $q->whereHas('status', function ($status) {
                    $status->where('step', '>', 0);
                })
                    ->orWhere(function ($hold) {

                        $hold->whereHas('status', function ($status) {
                            $status->where('step', 0);
                        })
                            ->where('reserved_until', '>', now());

                    });

            })
            ->get();


        $events = $orders->map(function ($order) {

            return [
                'title' => 'Not Available',
                'start' => $order->rent_start,
                'end'   => $order->rent_end,
                'display' => 'background',
                'color' => '#ffcccc',
            ];

        });

        return response()->json($events);
    }
}
