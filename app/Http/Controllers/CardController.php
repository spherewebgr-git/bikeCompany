<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => [
                'required',
                'string',
                'regex:/^\d{13,19}$/',
            ],
            'exp_month' => [
                'required',
                'integer',
                'between:1,12',
            ],
            'exp_year' => [
                'required',
                'integer',
                'min:' . now()->year,
            ],
            'cvv' => [
                'required',
                'string',
                'regex:/^\d{3,4}$/',
            ],
        ]);

        $request->user()->cards()->create($validated);

        return redirect()
            ->route('profile.edit', ['tab' => 'saved-cards'])
            ->with('status', 'card-added');
    }

    public function destroy(Request $request, Card $card): RedirectResponse
    {
        /*
         * Ελέγχουμε ότι η κάρτα ανήκει στον συνδεδεμένο χρήστη.
         */
        abort_unless(
            $card->user_id === $request->user()->id,
            403
        );

        $card->delete();

        return redirect()
            ->route('profile.edit', ['tab' => 'saved-cards'])
            ->with('status', 'card-deleted');
    }
}
