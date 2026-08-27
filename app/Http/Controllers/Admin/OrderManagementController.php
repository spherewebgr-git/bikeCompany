<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Bike;
use App\Models\Provision;
use App\Models\Location;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\OrderReadyMail;
use App\Mail\OrderDeliveredMail;
use Illuminate\Support\Facades\Mail;

class OrderManagementController extends Controller
{
    public function orders()
    {
        $complete = Status::max('step');

        $orders = Order::with([
            'user',
            'bike.provision',
            'status',
            'location',
        ])->whereHas('status', function ($query) use ($complete)
        { $query->where('step', '>', 0)->where('step', '<', $complete); })->get();

        return response()->json([
            'orders' => $orders,
            'users' => User::all(),
            'bikes' => Bike::all(),
            'provisions' => Provision::orderBy('name')->get(),
            'statuses' => Status::where('step', '>', 0)->orderBy("step")->get(),
            'locations' => Location::orderBy("name")->get(),
        ]);
    }

    public function update($id, Request $request)
    {
        $order = Order::findOrFail($id);
        $complete = Status::max('step');

        // Keep the previous status
        $previousStatus = $order->status->step;

        // Update status
        $order->update(['status_id' => $request->stat]);

        // Reload status
        $order->load('status');
        $isReady = $order->status->step == $complete-1; // "Ready"
        $wasAlreadyReady = $previousStatus == $complete-1; // "Ready"

        if ($isReady && !$wasAlreadyReady)
        {
            Mail::to($order->user->email)->queue(new OrderReadyMail($order));
        }

        $order->load('status');
        $isComplete = $order->status->step == $complete;
        $wasAlreadyComplete = $previousStatus == $complete;
        $isBuy = strtolower($order->bike->provision->name ?? '') === 'buy';

        if ($isComplete && !$wasAlreadyComplete && $isBuy)
        {
            Mail::to($order->user->email)->queue(new OrderDeliveredMail($order));
        }

        if ($isComplete && $order->payed_off == false)
        {
            $order->update(['payed_off' => true]);
        }

        if ($isComplete && !$wasAlreadyComplete && $isBuy)
        {
            $order->bike->quantity--;

            if ($order->bike->quantity < 1)
            {
                $order->bike->update(['visible' => false]);
            }
        }

        return response()->json([ 'message' => 'Order status updated successfully.', ]);
    }

    public function search(Request $request)
    {
        $complete = Status::max('step');

        $orders = Order::with([
            'user',
            'bike.provision',
            'status',
            'location',
        ])->whereHas('status', function ($query) use ($complete)
        { $query->where('step', '>', 0)->where('step', '<', $complete); });

        if ($request->filled('order'))
        {
            $orders->where('id', $request->order);
        }

        if ($request->filled('user'))
        {
            $orders->whereHas('user', function ($q) use ($request)
            {
                $q->where('first_name', 'LIKE', "%{$request->user}%")
                ->orWhere('last_name', 'LIKE', "%{$request->user}%")
                ->orWhere('phone', 'LIKE', "%{$request->user}%")
                ->orWhere('email', 'LIKE', "%{$request->user}%");

            });
        }

        if ($request->filled('product'))
        {
            $orders->whereHas('bike', function ($q) use ($request)
            {
                $q->where('SKU', 'LIKE', "%{$request->product}%")->where('serialnum', null)
                ->orWhere('serialnum', 'LIKE', "%{$request->product}%");
            });
        }

        if ($request->filled('provision'))
        {
            $orders->whereHas('bike.provision', function ($q) use ($request)
            {
                $q->where('id', $request->provision);
            });
        }

        if ($request->filled('location'))
        {
            $orders->whereHas('location', function ($q) use ($request)
            {
                $q->where('id', $request->location);
            });
        }

        if ($request->filled('status'))
        {
            $orders->whereHas('status', function ($q) use ($request)
            {
                $q->where('id', $request->status);
            });
        }

        if ($request->filled('payment'))
        {
            $orders->where('payed_off', $request->payment);
        }

        return response()->json([
            'orders' => $orders->get(),
            'users' => User::all(),
            'bikes' => Bike::all(),
            'provisions' => Provision::orderBy('name')->get(),
            'statuses' => Status::where('step', '>', 0)->orderBy("step")->get(),
            'locations' => Location::orderBy("name")->get(),
        ]);
    }
}