<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\Request;

class RentalBikeController extends Controller
{
    public function index()
    {
        $bikes = Bike::whereHas('provision', function ($query) {
            $query->where('name', 'rent');
        })->paginate(12);

        return view('bikes.rental.index', compact('bikes'));
    }
}
