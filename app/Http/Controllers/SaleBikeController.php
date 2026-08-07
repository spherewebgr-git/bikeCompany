<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Brand;
use App\Models\Price;
use App\Models\Type;
use App\Models\Speed;
use Illuminate\Http\Request;

class SaleBikeController extends Controller
{
    // SaleBikeController.php

    public function index(Request $request)
    {
        $query = Bike::whereHas('provision', function ($q) {
            $q->where('name', 'buy');
        });

        if(request('sort') == 'price_asc') {
            $query->join('prices', 'bikes.id', '=', 'prices.bike_id')
                ->orderByRaw('CAST(prices.price AS DECIMAL(10,2)) ASC')
                ->select('bikes.*');
        }

        if(request('sort') == 'price_desc') {
            $query->join('prices', 'bikes.id', '=', 'prices.bike_id')
                ->orderByRaw('CAST(prices.price AS DECIMAL(10,2)) DESC')
                ->select('bikes.*');
        }

        if ($request->filled('min_price')) {
            $query->whereHas('prices', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('prices', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

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

        return view('bikes.sale.index', compact('bikes', 'brands', 'types', 'speeds', 'colors'));
    }

    public function show(Bike $bike)
    {
        return view('bikes.sale.show', compact('bike'));
    }

// --------------------- \\
//   REACT CONTROLLER    \\
// --------------------- \\

    public function bikesforsale(Request $request)
    {
        $query = Bike::whereHas('provision', function ($q) {
            $q->where('name', 'buy');
        });

        if(request('sort') == 'price_asc') {

            $query->join('prices', 'bikes.id', '=', 'prices.bike_id')
                ->orderByRaw('CAST(prices.price AS DECIMAL(10,2)) ASC')
                ->select('bikes.*');

        }


        if(request('sort') == 'price_desc') {

            $query->join('prices', 'bikes.id', '=', 'prices.bike_id')
                ->orderByRaw('CAST(prices.price AS DECIMAL(10,2)) DESC')
                ->select('bikes.*');

        }

        if ($request->filled('min_price')) {
            $query->whereHas('prices', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('prices', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

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

        // return view('bikes.sale.index', compact(
        //     'bikes',
        //     'brands',
        //     'types',
        //     'speeds',
        //     'colors',
        // ));

        return response()->json
        ([
            'bikes' => $bikes,
            'brands' => $brands,
            'types' => $types,
            'speeds' => $speeds,
            'colors' => $colors,
        ]);

    }

    public function singlebikeforsale(Bike $bike)
    {
        //return view('bikes.sale.show', compact('bike'));
        return response()->json($bike);
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
