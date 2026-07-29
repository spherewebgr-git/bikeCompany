<?php

namespace App\Http\Controllers;

use App\Models\FeaturedBike;
use Illuminate\Http\Request;
use App\Models\Bike;

class HomeController extends Controller
{
    // HomeController@index
    public function index()
    {
        $featuredBikes = FeaturedBike::with('bike.brand', 'bike.type', 'bike.speed')
            ->orderBy('order')
            ->get()
            ->pluck('bike');

        return view('home', compact('featuredBikes'));
    }
}
