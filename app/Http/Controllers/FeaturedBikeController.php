<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Brand;
use App\Models\FeaturedBike;
use App\Models\Provision;
use App\Models\Speed;
use App\Models\Type;
use Illuminate\Http\Request;

// app/Http/Controllers/Admin/FeaturedBikeController.php
class FeaturedBikeController extends Controller
{
    public function edit(Request $request)
    {
        $query = Bike::with('brand');

        $featuredIds = FeaturedBike::orderBy('order')->pluck('bike_id');

        $availableBikes = $query->get()->whereNotIn('id', $featuredIds)->values();

        $featuredBikes = Bike::with('brand')
            ->whereIn('id', $featuredIds)
            ->get()
            ->sortBy(fn ($bike) => $featuredIds->search($bike->id))
            ->values();

        return view('staff.homepage.edit', [
            'availableBikes' => $availableBikes,
            'featuredBikes'  => $featuredBikes,
        ]);
    }

    // app/Http/Controllers/Admin/FeaturedBikeController.php — update()
    public function update(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('bike_ids', '')));

        $request->merge(['bike_ids' => $ids]);

        $data = $request->validate([
            'bike_ids'   => 'required|array|max:6',
            'bike_ids.*' => 'exists:bikes,id',
        ], [
            'bike_ids.max' => 'The maximum amount of featured bikes is 6.',
        ]);

        FeaturedBike::query()->delete();

        foreach ($data['bike_ids'] as $i => $bikeId) {
            FeaturedBike::create(['bike_id' => $bikeId, 'order' => $i]);
        }

        return back()->with('status', 'Featured bikes have been updated.');
    }

    public function featuredsearch(Request $request)
    {
        $allbikes = Bike::query();

        if ($request->filled('featsearch'))
        {
            $search = $request->featsearch;

            $allbikes->where(function ($q) use ($search)
            {
                $q->whereHas('brand', function ($q) use ($search)
                {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('type', function ($q) use ($search)
                {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('speed', function ($q) use ($search)
                {
                    $q->where('gears', 'LIKE', "%{$search}%");
                })
                ->orWhere('colour', 'LIKE', "%{$search}%");
            });
        }

        $availableBikes = $allbikes->get();

        $featuredIds = FeaturedBike::orderBy('order')->pluck('bike_id');

        $featuredBikes = Bike::with('brand')
            ->whereIn('id', $featuredIds)
            ->get()
            ->sortBy(fn ($bike) => $featuredIds->search($bike->id))
            ->values();


            return view('staff.homepage.edit', [
                'availableBikes' => $availableBikes,
                'featuredBikes'  => $featuredBikes,
            ]);

    }
}
