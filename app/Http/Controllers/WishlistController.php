<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{

    /**
     * Εμφανίζει τη Blade σελίδα που φιλοξενεί
     * το React WishlistPage component.
     */
    public function index(): View
    {
        return view('profile.wishlist');
    }

    /**
     * Επιστρέφει τα wishlist bikes του χρήστη
     * σε JSON μορφή για τη React.
     */
    public function items(Request $request): JsonResponse
    {
        $bikes = $request->user()
            ->wishlistBikes()
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
            });

        return response()->json([
            'success' => true,
            'bikes' => $bikes,
        ]);
    }

    /**
     * Επιστρέφει αν το συγκεκριμένο ποδήλατο
     * υπάρχει ήδη στη wishlist του χρήστη.
     */
    public function status(Request $request, Bike $bike): JsonResponse
    {
        $isWishlisted = $request->user()
            ->wishlistBikes()
            ->where('bikes.id', $bike->id)
            ->exists();

        return response()->json([
            'wishlisted' => $isWishlisted,
        ]);
    }

    /**
     * Προσθέτει το ποδήλατο στη wishlist.
     */
    public function store(Request $request, Bike $bike): JsonResponse
    {
        $request->user()
            ->wishlistBikes()
            ->syncWithoutDetaching([$bike->id]);

        return response()->json([
            'success' => true,
            'wishlisted' => true,
            'message' => 'Bike added to your wishlist.',
        ]);
    }

    /**
     * Αφαιρεί το ποδήλατο από τη wishlist.
     */
    public function destroy(Request $request, Bike $bike): JsonResponse
    {
        $request->user()
            ->wishlistBikes()
            ->detach($bike->id);

        return response()->json([
            'success' => true,
            'wishlisted' => false,
            'message' => 'Bike removed from your wishlist.',
        ]);
    }
}
