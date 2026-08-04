<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveRentalsController;

Route::get('/active-rentals', [ActiveRentalsController::class, 'activerentals']);

Route::patch('/active-rentals/{order}', [ActiveRentalsController::class, 'updatereturned']);