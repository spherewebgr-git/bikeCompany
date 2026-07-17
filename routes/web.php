<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalBikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleBikeController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\StaffmanagementController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

//Route::get('/', function () {
//    return view('welcome');
//});

////Navigation menu routes
//Route::get('/rental-bikes', [RentalBikeController::class, 'index'])
//    ->name('bikes.rental');
//
//
//Route::get('/sale-bikes', [SaleBikeController::class, 'index'])
//    ->name('bikes.sale');

// DASHBOARD

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// BIKE LIST PAGES
Route::get('/bikes/sale', [SaleBikeController::class, 'index'])->name('bikes.sale');
Route::get('/bikes/sale/{bike}', [SaleBikeController::class, 'show'])->name('bikes.sale.show');

Route::get('/bikes/rental', [RentalBikeController::class, 'index'])->name('bikes.rental');
Route::get('/bikes/rental/{bike}', [RentalBikeController::class, 'show']);


// USER PAGES
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// STAFF PAGES
Route::get('/dashboard/management/bikes', [StaffmanagementController::class, 'management'])->name('dashboard.management.bikes');
Route::get('/staff/bikes/filter', [StaffmanagementController::class, 'filter'])->name('bikes.filter');
Route::get('/bike/{id}', [StaffmanagementController::class, 'single'])->name('bike.view');
Route::post('/bike-create', [StaffmanagementController::class, 'create'])->name('bike.create');
Route::get('/bike-edit/{id}', [StaffmanagementController::class, 'edit'])->name('bike.edit');
Route::post('/bike-update/{id}', [StaffmanagementController::class, 'update'])->name('bike.update');
Route::get('/delete-bike/{id}', [StaffmanagementController::class, 'delete'])->name('bike.delete');

require __DIR__.'/auth.php';
