<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Card;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Order $order)
    {
        // Να μη μπορεί κάποιος να ανοίξει παραγγελία άλλου χρήστη
        abort_if($order->user_id != auth()->id(), 403);

        // Η παραγγελία έχει ήδη ολοκληρωθεί.

        if ($order->completed_at !== null) {
            return redirect()
                ->route('home')
                ->with(
                    'error',
                    'This order has already been completed.'
                );
        }

        if (
            $order->reserved_until === null ||
            $order->reserved_until->isPast()
        ) {
            return redirect()
                ->route('home')
                ->with(
                    'error',
                    'Your reservation has expired.'
                );
        }

        $order->load([
            'bike.brand',
            'bike.type',
            'bike.speed',
            'card',
            'user.cards'
        ]);

        return view('payment.index', compact('order'));
    }

    public function complete(Request $request, Order $order)
    {
        abort_if(
            (int) $order->user_id !== (int) auth()->id(),
            403
        );

        if ($order->completed_at !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been completed.',
            ], 409);
        }

        if (
            $order->reserved_until === null ||
            $order->reserved_until->isPast()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Your reservation has expired.',
            ], 409);
        }

        $request->validate([
            'payment_method' => [
                'required',
                'in:cash_on_delivery,card',
            ],

            'card_choice' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
            ],
        ], [
            'payment_method.required' => 'Please select a payment method.',
            'card_choice.required_if' => 'Please select a saved card or choose to use a new card.',
        ]);

        /*
         * Αντικαταβολή
         */
        if ($request->payment_method === 'cash_on_delivery') {
            $order->update([
                'payed_off' => false,
                'card_id' => null,
                'status_id' => 1,
                'completed_at' => now(),
                'reserved_until' => null,
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('home'),
            ]);
        }

        /*
         * Από εδώ και κάτω η πληρωμή είναι με κάρτα.
         */
        $cardChoice = $request->card_choice;
        $cardId = null;

        /*
         * Επιλογή αποθηκευμένης κάρτας.
         */
        if (str_starts_with($cardChoice, 'saved:')) {
            $cardId = (int) str_replace('saved:', '', $cardChoice);

            /*
             * Ελέγχουμε ότι η κάρτα υπάρχει και ανήκει
             * στον συνδεδεμένο χρήστη.
             */
            $card = $request->user()
                ->cards()
                ->whereKey($cardId)
                ->first();

            if (! $card) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected card is invalid.',
                ], 422);
            }

            $cardId = $card->id;
        }

        /*
         * Χρήση νέας κάρτας.
         */
        elseif ($cardChoice === 'new') {
            $validatedCard = $request->validate([
                'card_number' => [
                    'required',
                    'string',
                    'regex:/^\d{13,19}$/',
                ],
                'card_exp_month' => [
                    'required',
                    'integer',
                    'between:1,12',
                ],
                'card_exp_year' => [
                    'required',
                    'integer',
                    'min:' . now()->year,
                ],
                'card_cvv' => [
                    'required',
                    'string',
                    'regex:/^\d{3,4}$/',
                ],
            ], [
                'card_number.required' => 'Please enter the card number.',
                'card_number.regex' => 'The card number must contain 13 to 19 digits.',
                'card_exp_month.required' => 'Please enter the expiration month.',
                'card_exp_year.required' => 'Please enter the expiration year.',
                'card_cvv.required' => 'Please enter the CVV.',
            ]);

            /*
             * Αποθήκευση μόνο αν είναι επιλεγμένο το checkbox.
             */
            if ($request->boolean('save_card')) {
                $card = $request->user()->cards()->create([
                    'number' => $validatedCard['card_number'],
                    'exp_month' => $validatedCard['card_exp_month'],
                    'exp_year' => $validatedCard['card_exp_year'],
                    'cvv' => $validatedCard['card_cvv'],
                ]);

                $cardId = $card->id;
            }
        }

        /*
         * Άγνωστη ή μη έγκυρη επιλογή.
         */
        else {
            return response()->json([
                'success' => false,
                'message' => 'Please select a valid card option.',
            ], 422);
        }

        $order->update([
            'payed_off' => true,
            'card_id' => $cardId,
            'status_id' => 1,
            'completed_at' => now(),
            'reserved_until' => null,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('home'),
        ]);
    }
}
