<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveRentalsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatisticsController;

/******* AUTHENTICATION *******/
// PROBLEM: Doesn't work ☹️
Route::get('/user', function (Request $request)
{
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
Route::get('/admin/manage/categories', [CategoryController::class, 'index']);
Route::get('/admin/manage/categories/search', [CategoryController::class, 'search']);
Route::patch('/admin/manage/categories/{category}/{id}', [CategoryController::class, 'edit']);
Route::delete('/admin/manage/categories/{category}/{id}', [CategoryController::class, 'delete']);
Route::post('/admin/manage/categories/{category}', [CategoryController::class, 'create']);

// HOMEPAGE 


// PENDING ORDERS 
Route::get('/admin/manage/pending-orders', [OrderManagementController::class, 'orders']);
Route::get('/admin/manage/pending-orders/search', [OrderManagementController::class, 'search']);
Route::patch('/admin/manage/pending-orders/update/{id}', [OrderManagementController::class, 'update']);

// USERS 



//* ADMIN - TRACKING *//
// ACTIVE RENTALS
Route::get('/admin/track/active-rentals', [ActiveRentalsController::class, 'activerentals']);
Route::patch('/admin/track/active-rentals/{order}', [ActiveRentalsController::class, 'updatereturned']);

// PAST ORDERS
Route::get('/admin/track/past-orders', [OrderTrackingController::class, 'history']);
Route::get('/admin/track/past-orders/search', [OrderTrackingController::class, 'search']);

// STATISTICS
Route::get('/admin/track/statistics', [StatisticsController::class, 'statistics']);