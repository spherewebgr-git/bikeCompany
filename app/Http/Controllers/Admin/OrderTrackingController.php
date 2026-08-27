<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Bike;
use App\Models\Location;
use App\Models\Order;
use App\Models\Provision;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function history()
    {
        $complete = Status::max('step');

        $orders = Order::with([
            'user',
            'bike.provision',
            'status',
            'location',
        ])->whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete); })->get();

        return response()->json([
            'orders' => $orders,
            'users' => User::all(),
            'bikes' => Bike::all(),
            'provisions' => Provision::orderBy('name')->get(),
            'locations' => Location::orderBy("name")->get(),
        ]);
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
        { $query->where('step', $complete); });

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

        if ($request->filled('payment'))
        {
            $orders->where('payed_off', $request->payment);
        }

        return response()->json([
            'orders' => $orders->get(),
            'users' => User::all(),
            'bikes' => Bike::all(),
            'provisions' => Provision::orderBy('name')->get(),
            'locations' => Location::orderBy("name")->get(),
        ]);
    }
}



