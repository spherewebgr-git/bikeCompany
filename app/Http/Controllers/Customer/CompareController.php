<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;

use App\Models\Bike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompareController extends Controller
{
    public function index(): View
    {
        return view('profile.compare');
    }

    public function items(Request $request): JsonResponse
    {
        $bikes = $request->user()
            ->compareBikes()
            ->with([
                'brand',
                'type',
                'speed',
                'provision',
                'prices',
                'images',
            ])
            ->get()
            ->map(function (Bike $bike) {
                return [
                    'id' => $bike->id,
                    'sku' => $bike->SKU,
                    'colour' => $bike->colour,
                    'quantity' => $bike->quantity,

                    'brand' => $bike->brand?->name,
                    'type' => $bike->type?->name,
                    'gears' => $bike->speed?->gears,
                    'provision' => ucfirst($bike->provision?->name ?? ''),

                    'prices' => $bike->prices
                        ->map(function ($price) {
                            return [
                                'id' => $price->id,
                                'price' => $price->price,
                                'description' => $price->description,
                            ];
                        })
                        ->values(),

                    'image' => $bike->images->first()?->image,

                    'show_url' => strtolower(
                        $bike->provision?->name ?? ''
                    ) === 'rent'
                        ? route('bikes.rental.show', $bike)
                        : route('bikes.sale.show', $bike),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'bikes' => $bikes,
            'count' => $bikes->count(),
            'maximum' => 3,
        ]);
    }

    public function store(
        Request $request,
        Bike $bike
    ): JsonResponse {
        $user = $request->user();

        $alreadyCompared = $user
            ->compareBikes()
            ->where('bikes.id', $bike->id)
            ->exists();

        if ($alreadyCompared) {
            return response()->json([
                'success' => true,
                'compared' => true,
                'count' => $user->compareBikes()->count(),
                'maximum' => 3,
                'message' => 'Bike is already selected for comparison.',
            ]);
        }

        $currentCount = $user
            ->compareBikes()
            ->count();

        if ($currentCount >= 3) {
            return response()->json([
                'success' => false,
                'compared' => false,
                'count' => $currentCount,
                'maximum' => 3,
                'message' => 'You can compare up to 3 bikes.',
            ], 422);
        }

        $user
            ->compareBikes()
            ->attach($bike->id);

        return response()->json([
            'success' => true,
            'compared' => true,
            'count' => $currentCount + 1,
            'maximum' => 3,
            'message' => 'Bike added to comparison.',
        ]);
    }

    /**
     * Αφαιρεί ένα bike από τη σύγκριση.
     */
    public function destroy(
        Request $request,
        Bike $bike
    ): JsonResponse {
        $user = $request->user();

        $user
            ->compareBikes()
            ->detach($bike->id);

        return response()->json([
            'success' => true,
            'compared' => false,
            'count' => $user->compareBikes()->count(),
            'maximum' => 3,
            'message' => 'Bike removed from comparison.',
        ]);
    }
}
