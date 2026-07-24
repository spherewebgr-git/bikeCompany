<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalBikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleBikeController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\StaffmanagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CardController;

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
    Route::get('/sale/checkout/{bike}', [CheckoutController::class, 'createSale'])->name('checkout.create-sale');
    Route::post('/sale/checkout/{bike}', [CheckoutController::class, 'storeSale'])->name('checkout.store-sale');

    Route::get('/payment/{order}', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment/{order}', [PaymentController::class, 'complete'])->name('payment.complete');
    Route::delete('/payment/{order}/expire', [PaymentController::class, 'expire'])->name('payment.expire');

    Route::get('/rental/checkout/{bike}', [CheckoutController::class, 'createRental'])->name('checkout.create-rental');
    Route::post('/rental/checkout/{bike}', [CheckoutController::class, 'storeRental'])->name('checkout.store-rental');
});

//RENTAL AVAILABILITY
Route::get('/bikes/{bike}/availability', [RentalBikeController::class, 'availability'])->name('bikes.availability');


// USER PAGES
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/profile/cards', [CardController::class, 'store'])->name('profile.cards.store');
    Route::delete('/profile/cards/{card}', [CardController::class, 'destroy'])->name('profile.cards.destroy');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/history', [ProfileController::class, 'history'])->name('profile.history');

    Route::get('/profile/history/search', [ProfileController::class, 'searchhistory'])->name('profile.history.search');

    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');

    Route::get('/profile/orders/search', [ProfileController::class, 'ordersearch'])->name('profile.orders.search');
});


// STAFF PAGES
Route::middleware(['auth', 'role:staff'])->group(function () {

    // --------- BIKES --------- \\
    Route::get('/dashboard/management/bikes', [StaffmanagementController::class, 'management'])->name('dashboard.management.bikes');

    Route::get('/staff/bikes/filter', [StaffmanagementController::class, 'filter'])->name('bikes.filter');

    Route::get('/staff/bikes/search', [StaffmanagementController::class, 'search'])->name('bikes.search');

    Route::get('/bike/{id}', [StaffmanagementController::class, 'single'])->name('bike.view');

    Route::post('/bike-create', [StaffmanagementController::class, 'create'])->name('bike.create');

    Route::get('/bike-edit/{id}', [StaffmanagementController::class, 'edit'])->name('bike.edit');

    Route::post('/bike-update/{id}', [StaffmanagementController::class, 'update'])->name('bike.update');

    Route::get('/delete-bike/{id}', [StaffmanagementController::class, 'delete'])->name('bike.delete');

    Route::post('/bike-quantity/{id}', [StaffmanagementController::class, 'quantity'])->name('bike.quantity');


    // --------- USERS --------- \\
    Route::get('/staff/users',[StaffmanagementController::class, 'users'])->name('staff.users.index');

    Route::patch('/staff/users/{user}/promote', [StaffmanagementController::class, 'promoteToStaff'])->name('staff.users.promote');

    Route::patch('/staff/users/{user}/demote', [StaffmanagementController::class, 'demoteToCustomer'])->name('staff.users.demote');

    // --------- CATEGORIES --------- \\
    Route::get('/dashboard/management/categories', [StaffmanagementController::class, 'managecategories'])->name('dashboard.management.categories');

    Route::get('/dashboard/management/categories/search', [StaffmanagementController::class, 'searchcategory'])->name('category.search');

    Route::get('/dashboard/management/categories/delete-{id}-{category}', [StaffmanagementController::class, 'deletecategory'])->name('category.delete');

    Route::post('/dashboard/management/categories/create-{category}', [StaffmanagementController::class, 'newcategory'])->name('category.create');

    // --------- ORDERS --------- \\
    Route::get('/dashboard/management/pendingorders', [StaffmanagementController::class, 'manageorders'])->name('dashboard.management.orders');

    Route::post('/dashboard/management/order-update/{id}', [StaffmanagementController::class, 'orderupdate'])->name('order.update');

    Route::get('/dashboard/management/orders/search', [StaffmanagementController::class, 'searchorder'])->name('order.search');

   // --------- ARCHIVE --------- \\
    Route::get('/dashboard/management/orderhistory', [StaffmanagementController::class, 'history'])->name('dashboard.management.orderhistory');

    Route::get('/dashboard/management/orderhistory/search', [StaffmanagementController::class, 'searchhistory'])->name('orderhistory.search');
});

require __DIR__.'/auth.php';
