<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Location;
use App\Models\Order;
use App\Models\User;

class StatisticsController extends Controller
{
    public function statistics()
    {
        $locations = Location::all();
        $locsales = [];

        foreach ($locations as $location)
        {
            $locsales[$location->name] =
            [
                'location' => $location->name,
                'profit' => 0.0,
                'sales' => 0,
            ];
        }

        $orders = Order::all();

        $totalorders = 0;
        $neworders = 0;
        $totalprofit = 0;
        $newprofit = 0;
        $rents = 0;
        $purchases = 0;

        foreach ($orders as $order)
        {
            $totalprofit += $order->price;
            $totalorders ++;

            if ($order->order_date?->isToday())
            {
                $newprofit += $order->price;
                $neworders ++;
            }

            if ($order->rent_start !== null)
            {
                $rents ++;

                $loc = $order->location?->name;
                if ($loc)
                {
                    $locsales[$loc]['profit'] += $order->price;
                    $locsales[$loc]['sales'] ++;
                }
            }
            else
            {
                $purchases ++;
            }
        }

        $users = User::all();

        $totalusers = 0;
        $newusers = 0;

        foreach ($users as $user)
        {
            $totalusers ++;

            if ($user->created_at?->isToday())
            {
                $newusers ++;
            }
        }

        return response()->json([
            'totalorders' => $totalorders,
            'neworders' => $neworders,
            'totalprofit' => round($totalprofit, 2),
            'newprofit' => round($newprofit, 2),
            'totalusers' => $totalusers,
            'newusers' => $newusers,
            'rents' => $rents,
            'purchases' => $purchases,
            'locsales' => array_values($locsales),
        ]);
    }
}



