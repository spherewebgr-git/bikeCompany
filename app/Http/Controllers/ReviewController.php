<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Επιστρέφει όλα τα reviews ενός bike.
     */
    public function index(
        Request $request,
        Bike $bike
    ): JsonResponse {

        $reviews = $bike
            ->reviews()
            ->with('user')
            ->latest()
            ->get()
            ->map(function (Review $review) use ($request) {

                return [
                    'id' => $review->id,

                    'rating' => $review->rating,
                    'comment' => $review->comment,

                    'user' => [
                        'id' => $review->user->id,
                        'first_name' => $review->user->first_name,
                        'last_name' => $review->user->last_name,
                    ],

                    'is_owner' =>
                        $request->user()?->id === $review->user_id,

                    'created_at' =>
                        $review->created_at->format('d/m/Y'),
                ];
            });

        return response()->json([
            'success' => true,

            'reviews' => $reviews,

            'count' => $reviews->count(),

            'average_rating' => round(
                $bike->reviews()->avg('rating') ?? 0,
                1
            ),
        ]);
    }


    /**
     * Δημιουργία review.
     */
    public function store(
        Request $request,
        Bike $bike
    ): JsonResponse {

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $user = $request->user();

        $alreadyReviewed = $user
            ->reviews()
            ->where('bike_id', $bike->id)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'success' => false,
                'message' =>
                    'You have already reviewed this bike.',
            ], 422);
        }

        $review = $user
            ->reviews()
            ->create([
                'bike_id' => $bike->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Review added successfully.',

            'review' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,

                'user' => [
                    'id' => $review->user->id,
                    'first_name' => $review->user->first_name,
                    'last_name' => $review->user->last_name,
                ],

                'is_owner' => true,

                'created_at' =>
                    $review->created_at->format('d/m/Y'),
            ],
        ], 201);
    }


    /**
     * Επεξεργασία review.
     */
    public function update(
        Request $request,
        Review $review
    ): JsonResponse {

        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' =>
                    'You cannot edit this review.',
            ], 403);
        }

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully.',
        ]);
    }


    /**
     * Διαγραφή review.
     */
    public function destroy(
        Request $request,
        Review $review
    ): JsonResponse {

        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' =>
                    'You cannot delete this review.',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.',
        ]);
    }
}
