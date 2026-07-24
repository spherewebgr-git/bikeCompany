<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Order;
use App\Models\Card;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderCompletedMail;
use Illuminate\Support\Facades\Mail;

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
        abort_if($order->user_id !== auth()->id(),
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
                'status_id' => Status::where('step', 1)->first()->id,
                'completed_at' => now(),
                'reserved_until' => null,
            ]);


            $order->load([
                'user',
                'status',
                'bike.brand',
                'bike.type',
            ]);

            Mail::to($order->user->email)
                ->queue(new OrderCompletedMail($order));

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
            'status_id' => Status::where('step', 1)->first()->id,
            'completed_at' => now(),
            'reserved_until' => null,
        ]);

        $order->load([
            'user',
            'status',
            'bike.brand',
            'bike.type',
        ]);

        /*
         * Το email δεν αποστέλλεται μέσα στο request.
         * Αποθηκεύεται στην queue και θα σταλεί
         * από τον queue worker.
        */
        Mail::to($order->user->email)
            ->queue(new OrderCompletedMail($order));

        return response()->json([
            'success' => true,
            'redirect' => route('home'),
        ]);
    }

    public function expire(Order $order)
    {
        if ($order->user_id !== (int) auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to access this order.',
            ], 403);
        }

        try {
            DB::transaction(function () use ($order) {

                /*
                 * Κλειδώνουμε την παραγγελία ώστε να μην μπορεί
                 * να ολοκληρωθεί και να διαγραφεί ταυτόχρονα.
                 */
                $lockedOrder = Order::query()
                    ->whereKey($order->id)
                    ->lockForUpdate()
                    ->first();

                /*
                 * Μπορεί να έχει ήδη διαγραφεί από άλλο request.
                 */
                if (!$lockedOrder) {
                    return;
                }

                /*
                 * Αν έχει ολοκληρωθεί, δεν επιστρέφουμε quantity
                 * και δεν διαγράφουμε την παραγγελία.
                 */
                if ($lockedOrder->completed_at !== null) {
                    throw new \RuntimeException(
                        'This order has already been completed.'
                    );
                }

                /*
                 * Ο server επιβεβαιώνει ότι ο χρόνος έχει όντως λήξει.
                 * Δεν εμπιστευόμαστε μόνο το JavaScript countdown.
                 */
                if (
                    $lockedOrder->reserved_until !== null &&
                    $lockedOrder->reserved_until->isFuture()
                ) {
                    throw new \RuntimeException(
                        'The reservation has not expired yet.'
                    );
                }

                /*
                 * Κλειδώνουμε και το ποδήλατο πριν αλλάξουμε το stock.
                 */
                $lockedBike = Bike::query()
                    ->whereKey($lockedOrder->bike_id)
                    ->lockForUpdate()
                    ->first();

                if ($lockedBike) {
                    $lockedBike->increment('quantity');
                }

                /*
                 * Η προσωρινή παραγγελία δεν χρειάζεται πλέον.
                 */
                $lockedOrder->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'The reservation expired and the bike quantity was restored.',
                'redirect' => route('home'),
            ]);

        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 409);
        }
    }
}
