<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveRentalsController;
use App\Http\Controllers\ProductController;


/******* AUTHENTICATION *******/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/******* PUBLIC PAGES *******/
//* HOME *//
// ABOUT US
Route::get('/featured-bikes', [HomeController::class, 'apiFeatured']);

//* BIKES FOR SALE *//
// BROWSE


// DETAILS


// CHECKOUT


// PAYMENT


//* RENTAL BIKES *//
// BROWSE


// DETAILS


// CHECKOUT


// PAYMENT


//* CONTACT US *//


//* PROFILE *//
// ACCOUNT


// WISHLIST


// MY ORDERS


// HISTORY



/******* ADMIN PAGES *******/
//* ADMIN - MANAGEMENT *//
// PRODUCTS 
Route::get('/admin/manage/products', [ProductController::class, 'management']);
Route::patch('/admin/manage/products/{id}/quantity', [ProductController::class, 'quantity']);
Route::get('/admin/manage/products/edit/{id}', [ProductController::class, 'edit']);
Route::patch('/admin/manage/products/update/{id}', [ProductController::class, 'update']);
Route::get('/admin/manage/products/create', [ProductController::class, 'create']);
Route::post('/admin/manage/products/add', [ProductController::class, 'add']);

// CALENDAR 


// CATEGORIES 
Route::patch('/admin/manage/categories/{category}/{id}', [ProductController::class, 'edit']);
Route::get('/admin/manage/categories/search', [ProductController::class, 'search']);
Route::patch('/admin/manage/categories/{category}/{id}', [ProductController::class, 'edit']);
Route::delete('/admin/manage/categories/{category}/{id}', [ProductController::class, 'delete']);

// HOMEPAGE 


// PENDING ORDERS 


// USERS 



//* ADMIN - TRACKING *//
// ACTIVE RENTALS
Route::get('/active-rentals', [ActiveRentalsController::class, 'activerentals']);
Route::patch('/active-rentals/{order}', [ActiveRentalsController::class, 'updatereturned']);

// PAST ORDERS


// STATISTICS 