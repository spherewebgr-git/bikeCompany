<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\Request;

class BikeController extends Controller
{
    public function index()
    {
        $bikes = Bike::with([
            'brand',
            'type',
            'speed',
            'provision',
            'prices'
        ])->get();

        return view('bikes.index', compact('bikes'));
    }
}
