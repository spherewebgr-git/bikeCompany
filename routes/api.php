<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveRentalsController;
use App\Http\Controllers\RentalBikeController;
use App\Http\Controllers\SaleBikeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/featured-bikes', [HomeController::class, 'apiFeatured']);

Route::get('/active-rentals', [ActiveRentalsController::class, 'activerentals']);

Route::patch('/active-rentals/{order}', [ActiveRentalsController::class, 'updatereturned']);


// BIKES FOR SALE
Route::get('/bikes/sale', [SaleBikeController::class, 'bikesforsale']);
Route::get('/bikes/sale/{bike}', [SaleBikeController::class, 'singlebikeforsale']);

// BIKES FOR RENT
// Route::get('/bikes/rental', [RentalBikeController::class, 'bikesforrent']);
// Route::get('/bikes/rental/{bike}', [RentalBikeController::class, 'singlebikeforrent']);
