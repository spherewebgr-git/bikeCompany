<?php

use App\Http\Controllers\CheckoutController;
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
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// BIKE LIST PAGES
Route::get('/bikes/sale', [SaleBikeController::class, 'index'])->name('bikes.sale');
Route::get('/bikes/sale/{bike}', [SaleBikeController::class, 'show'])->name('bikes.sale.show');

Route::get('/bikes/rental', [RentalBikeController::class, 'index'])->name('bikes.rental');
Route::get('/bikes/rental/{bike}', [RentalBikeController::class, 'show'])->name('bikes.rental.show');


//PURCHASE
Route::middleware('auth')->group(function () {
    Route::get('/checkout/{bike}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{bike}', [CheckoutController::class, 'store'])->name('checkout.store');
});


// USER PAGES
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// STAFF PAGES
Route::middleware(['auth', 'role:staff'])->group(function () {

    // --------- BIKES --------- \\
    Route::get('/dashboard/management/bikes', [StaffmanagementController::class, 'management'])->name('dashboard.management.bikes');

    Route::get('/staff/bikes/filter', [StaffmanagementController::class, 'filter'])->name('bikes.filter');

    Route::get('/staff/bikes/search', [StaffmanagementController::class, 'search'])->name('bikes.search');

    Route::get('/staff/bikes/filter', [StaffmanagementController::class, 'filter'])->name('bikes.filter');

    Route::get('/bike/{id}', [StaffmanagementController::class, 'single'])->name('bike.view');

    Route::post('/bike-create', [StaffmanagementController::class, 'create'])->name('bike.create');

    Route::get('/bike-edit/{id}', [StaffmanagementController::class, 'edit'])->name('bike.edit');

    Route::post('/bike-update/{id}', [StaffmanagementController::class, 'update'])->name('bike.update');

    Route::get('/delete-bike/{id}', [StaffmanagementController::class, 'delete'])->name('bike.delete');


    // --------- USERS --------- \\
    Route::get('/staff/users',[StaffmanagementController::class, 'users'])->name('staff.users.index');

    Route::patch('/staff/users/{user}/promote', [StaffmanagementController::class, 'promoteToStaff'])->name('staff.users.promote');

    Route::patch('/staff/users/{user}/demote', [StaffmanagementController::class, 'demoteToCustomer'])->name('staff.users.demote');

    // --------- CATEGORIES --------- \\
    Route::get('/dashboard/management/categories', [StaffmanagementController::class, 'managecategories'])->name('dashboard.management.categories');

    Route::get('/staff/categories/categories', [StaffmanagementController::class, 'searchcategory'])->name('category.search');

    Route::get('/delete-category/{id}', [StaffmanagementController::class, 'deletecategory'])->name('category.delete');

    Route::post('/category-create', [StaffmanagementController::class, 'newcategory'])->name('category.create');
});

require __DIR__.'/auth.php';
