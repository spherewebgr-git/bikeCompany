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
        abort_if($order->user_id != auth()->id(), 403);

        $request->validate([
            'payment_method' => 'required|in:cash_on_delivery,card',
        ]);

        /*
         * Αντικαταβολή
         */
        if ($request->payment_method === 'cash_on_delivery') {

            $order->update([
                'payed_off' => false,
                'card_id' => null,
            ]);

            return response()->json([
                'success' => true,
            ]);
        }

        /*
         * Πληρωμή με κάρτα
         */

        $cardId = null;

        /*
         * Έχει επιλεγεί αποθηκευμένη κάρτα
         */
        if (
            str_starts_with($request->card_choice, 'saved:')
        ) {

            $cardId = str_replace(
                'saved:',
                '',
                $request->card_choice
            );

        } else {

            /*
             * Νέα κάρτα
             */

            if ($request->boolean('save_card')) {

                $card = Card::create([
                    'number' => $request->card_number,
                    'exp_month' => $request->card_exp_month,
                    'exp_year' => $request->card_exp_year,
                    'cvv' => $request->card_cvv,
                    'user_id' => auth()->id(),
                ]);

                $cardId = $card->id;
            }

        }

        $order->update([
            'payed_off' => true,
            'card_id' => $cardId,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
