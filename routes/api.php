<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveRentalsController;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/featured-bikes', [HomeController::class, 'apiFeatured']);

Route::get('/active-rentals', [ActiveRentalsController::class, 'activerentals']);

Route::patch('/active-rentals/{order}', [ActiveRentalsController::class, 'updatereturned']);

// ADMIN > MANAGEMENT
Route::get('/admin/manage/products', [ProductController::class, 'management']);