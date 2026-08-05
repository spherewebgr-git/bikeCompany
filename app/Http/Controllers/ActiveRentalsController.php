<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Mail\OrderDeliveredMail;
use Illuminate\Support\Facades\Mail;

class ActiveRentalsController extends Controller
{
    public function activerentals(Request $request)
    {
        $complete = Status::max('step');
        $orders = Order::with(['bike.images', 'location', 'user', 'status'])
        ->whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete)->where('rent_end', '<>', null)->where('returned', false); });

        if ($request->query('return') === 'overdue')
        {
            $orders = $orders->where('rent_end', '<', now());
        }

        if ($request->query('return') === 'pending')
        {
            $orders = $orders->where('rent_end', '>=', now());
        }

        return response()->json($orders->orderBy('rent_end')->get());
    }

    public function updatereturned(Order $order)
    {
        if ($order->returned)
        {
            return response()->json(['message' => 'Order already returned.'], 400);
        }

        $order->returned = true;
        if ($order->rent_end) { $order->rent_end = now(); }

        $order->save();

        $order->load([
            'user',
            'location',
            'bike.provision',
            'bike.brand',
            'bike.type',
            'bike.speed',
        ]);

        Mail::to($order->user->email)->queue(new OrderDeliveredMail($order));

        return response()->json
        ([
            'message' => 'Rental marked as returned.',
            'order' => $order,
        ]);
    }

}