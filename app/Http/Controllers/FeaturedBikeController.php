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

        if ($request->filled('SKU')) {
            $query->where('SKU', 'LIKE', "%{$request->SKU}%");
        }

        if ($request->filled('provision')) {
            $query->whereHas('provision', fn ($q) => $q->where('name', $request->provision));
        }

        if ($request->filled('brand')) {
            $query->whereHas('brand', fn ($q) => $q->where('name', $request->brand));
        }

        if ($request->filled('type')) {
            $query->whereHas('type', fn ($q) => $q->where('name', $request->type));
        }

        if ($request->filled('gears')) {
            $query->whereHas('speed', fn ($q) => $q->where('gears', $request->gears));
        }

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
            'brands'         => Brand::all()->sortBy('name'),
            'types'          => Type::all()->sortBy('name'),
            'provisions'     => Provision::all()->sortBy('name'),
            'speeds'         => Speed::all()->sortBy('gears'),
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
            'bike_ids.max' => 'Μπορείτε να επιλέξετε το πολύ 6 ποδήλατα.',
        ]);

        FeaturedBike::query()->delete();

        foreach ($data['bike_ids'] as $i => $bikeId) {
            FeaturedBike::create(['bike_id' => $bikeId, 'order' => $i]);
        }

        return back()->with('status', 'Τα featured bikes ενημερώθηκαν.');
    }
}
