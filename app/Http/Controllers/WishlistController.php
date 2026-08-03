<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
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
