<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bike;

class HomeController extends Controller
{
    public function index() {
        $featuredBikes = Bike::latest()->take(6)->get(); // TODO:Θα αλλάξει μελλοντικά να μπορεί το staff να διαλέγει ποια bikes θα εμφανίζονται!

        return view('home', compact('featuredBikes'));
    }
}
