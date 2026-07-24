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
            ->blocking()
            ->get();

        $events = $orders->map(fn ($order) => [
            'title'   => 'Not Available',
            'start'   => $order->rent_start->toIso8601String(),
            'end'     => $order->rent_end->toIso8601String(),
            'display' => 'background',
            'color'   => '#ffcccc',
        ]);

        return response()->json($events);
    }
}
