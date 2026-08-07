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
        $query = Bike::where('visible', true)->whereHas('provision', function ($q) {
            $q->where('name', 'rent');
        });

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('type')) {
            $query->where('type_id', $request->type);
        }

        if ($request->filled('speed')) {
            $query->where('speed_id', $request->speed);
        }

        if ($request->filled('color')) {
            $query->where('colour', $request->color);
        }

        // Search (brand, type, speed, colour)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('brand', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('type', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('speed', function ($q) use ($search) {
                        $q->where('gears', 'LIKE', "%{$search}%");
                    })
                    ->orWhere('colour', 'LIKE', "%{$search}%");
            });
        }

        $bikes = $query->paginate(12)->withQueryString();

        $brands = Brand::orderBy('name')->get();
        $types = Type::orderBy('name')->get();
        $speeds = Speed::orderBy('gears')->get();

        $colors = Bike::select('colour')->distinct()->orderBy('colour')->pluck('colour');

        return view('bikes.rental.index', compact('bikes', 'brands', 'types', 'speeds', 'colors'));
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

        $orderEvents = $orders->map(fn ($order) => [
            'title'   => 'Not Available',
            'start'   => $order->rent_start->format('Y-m-d H:i:s'),
            'end'     => $order->rent_end->format('Y-m-d H:i:s'),
            'display' => 'background',
            'color'   => '#ffcccc',
        ]);

        $blockedDates = \App\Models\BlockedDate::forBike($bike->id)->get();

        $blockedEvents = $blockedDates->map(fn ($blocked) => [
            'title'   => $blocked->reason ?? 'Booked',
            'start'   => $blocked->start_date->format('Y-m-d'),
            'end'     => $blocked->end_date->format('Y-m-d'),
            'display' => 'background',
            'color'   => '#ffcccc',
        ]);

        $events = $orderEvents->concat($blockedEvents)->values();

        return response()->json($events);
    }

    public function searchSuggestions(Request $request)
    {
        $search = $request->get('q');

        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        $brands = Brand::where('name', 'LIKE', "%{$search}%")->pluck('name');
        $types = Type::where('name', 'LIKE', "%{$search}%")->pluck('name');
        $colors = Bike::where('colour', 'LIKE', "%{$search}%")->distinct()->pluck('colour');

        $suggestions = $brands->merge($types)->merge($colors)
            ->unique()
            ->take(8)
            ->values();

        return response()->json($suggestions);
    }

}
