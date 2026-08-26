<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Provision;
use App\Models\Status;
use Illuminate\Http\Request;


class CustomerOrdersController extends Controller
{
    public function orders(Request $request)
    {
        $user = $request->user();
        $complete = Status::max('step');
        $orders = Order::with([
            'bike.images',
            'bike.speed',
            'bike.brand',
            'bike.type',
            'bike.provision',
            'status',
            'location',
        ])->where('user_id', $user->id)->whereHas('status', function ($query) use ($complete)
        { $query->where('step', '<>', $complete)->where('step', '>', 0); })->get();

        return response()->json([
            'orders' => $orders,
            'provisions' => Provision::orderBy('name')->get(),
            'statuses' => Status::where('step', '>', 0)->orderBy("step")->get(),
        ]);
    }

    public function searchorders(Request $request)
    {
        $user = $request->user();
        $complete = Status::max('step');
        $orders = Order::with([
            'bike.images',
            'bike.speed',
            'bike.brand',
            'bike.type',
            'bike.provision',
            'status',
            'location',
        ])->where('user_id', $user->id)->whereHas('status', function ($query) use ($complete)
        { $query->where('step', '<>', $complete)->where('step', '>', 0); });

        if ($request->filled('orderdate'))
        {
            $orders->whereRaw("DATE(order_date) LIKE ?", ["{$request->orderdate}%"]);
        }

        if ($request->filled('order'))
        {
            $orders->where('id', $request->order);
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

        if ($request->filled('pickup'))
        {
            $orders->where(function ($query) use ($request)
            {
                $query->where('dropoff_address', 'LIKE', "%{$request->pickup}%")
                    ->orWhereHas('location', function ($q) use ($request)
                    {
                        $q->where('name', 'LIKE', "%{$request->pickup}%");
                    });
            });
        }

        if ($request->filled('min_price'))
        {
            $orders->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price'))
        {
            $orders->where('price', '<=', $request->max_price);
        }      
        
        return response()->json([
            'orders' => $orders->get(),
            'provisions' => Provision::orderBy('name')->get(),
            'statuses' => Status::where('step', '>', 0)->orderBy("step")->get(),
        ]);
    }

    //-------------------------------------------//
    //                 HISTORY                   //
    //-------------------------------------------//

    public function history(Request $request)
    {
        $user = $request->user();
        $complete = Status::max('step');    
        $orders = Order::with([
            'bike.images',
            'bike.speed',
            'bike.brand',
            'bike.type',
            'bike.provision',
            'status',
            'location', // TODO: $user->id instead of "1"
        ])->where('user_id', 1)->whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete); })->get();

        return response()->json([
            'orders' => $orders,
            'provisions' => Provision::orderBy('name')->get(),
        ]);
    }

    public function searchhistory(Request $request)
    {
        $user = $request->user();
        $complete = Status::max('step');
        $orders = Order::with([
            'bike.images',
            'bike.speed',
            'bike.brand',
            'bike.type',
            'bike.provision',
            'status',
            'location', // TODO: $user->id instead of "1"
        ])->where('user_id', 1)->whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete); });

        if ($request->filled('orderdate'))
        {
            $orders->whereRaw("DATE(order_date) LIKE ?", ["{$request->orderdate}%"]);
        }

        if ($request->filled('order'))
        {
            $orders->where('id', $request->order);
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

        if ($request->filled('pickup'))
        {
            $orders->where(function ($query) use ($request)
            {
                $query->where('dropoff_address', 'LIKE', "%{$request->pickup}%")
                    ->orWhereHas('location', function ($q) use ($request)
                    {
                        $q->where('name', 'LIKE', "%{$request->pickup}%");
                    });
            });
        }

        if ($request->filled('min_price'))
        {
            $orders->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price'))
        {
            $orders->where('price', '<=', $request->max_price);
        }        
        
        return response()->json([
            'orders' => $orders->get(),
            'provisions' => Provision::orderBy('name')->get(),
        ]);
    }
}
