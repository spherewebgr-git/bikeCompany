<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\Request;

class SaleBikeController extends Controller
{
    public function index()
    {
        $bikes = Bike::whereHas('provision', function ($query) {
            $query->where('name', 'buy');
        })->paginate(12);

        return view('bikes.sale.index', compact('bikes'));
    }

//    public function show(Bike $bike)
//    {
//        return view('bikes.sale.single', compact('bike'));
//    }
}
