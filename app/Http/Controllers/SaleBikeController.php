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

        return view('bikes.sale.index', compact(
            'bikes',
            'brands',
            'types',
            'speeds',
            'colors',
        ));

    }

    public function show(Bike $bike)
    {
        return view('bikes.sale.show', compact('bike'));
    }
}
