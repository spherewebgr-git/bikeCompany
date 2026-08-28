<?php

use App\Http\Controllers\PromoBannerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// BLADE CONTROLLERS
use App\Http\Controllers\BlockedDateController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FeaturedBikeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalBikeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SaleBikeController;
use App\Http\Controllers\StaffmanagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CardController;

// REACT CONTROLLERS
use App\Http\Controllers\Admin\ActiveRentalsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\OrderTrackingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Customer\CompareController;
use App\Http\Controllers\Customer\OrdersController;
use App\Http\Controllers\Customer\WishlistController;


// HOMEPAGE
Route::get('/', [HomeController::class, 'index'])->name('home');

// DASHBOARD
Route::get('/dashboard', function ()
{
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// BIKE LIST PAGES
Route::get('/bikes/sale', [SaleBikeController::class, 'index'])->name('bikes.sale');
Route::get('/bikes/sale/{bike}', [SaleBikeController::class, 'show'])->name('bikes.sale.show');

Route::get('/bikes/rental', [RentalBikeController::class, 'index'])->name('bikes.rental');
Route::get('/bikes/rental/{bike}', [RentalBikeController::class, 'show'])->name('bikes.rental.show');

Route::get('/rental-bikes/suggestions', [RentalBikeController::class, 'searchSuggestions'])->name('bikes.rental.suggestions');

// CONTACT US PAGE
Route::get('/contact-us', [ContactUsController::class, 'index'])->name('contact-us');
Route::post('/contact-us', [ContactUsController::class, 'send'])->name('contact-us.send');

//REVIEWS
Route::get('/bikes/{bike}/reviews', [ReviewController::class, 'index'])->name('bikes.index');



Route::middleware('auth')->group(function () {
    //PURCHASE
    Route::get('/sale/checkout/{bike}', [CheckoutController::class, 'createSale'])->name('checkout.create-sale');
    Route::post('/sale/checkout/{bike}', [CheckoutController::class, 'storeSale'])->name('checkout.store-sale');

    Route::get('/payment/{order}', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment/{order}', [PaymentController::class, 'complete'])->name('payment.complete');
    Route::delete('/payment/{order}/expire', [PaymentController::class, 'expire'])->name('payment.expire');
    Route::delete('payment/{order}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

    //RENTAL
    Route::get('/rental/checkout/{bike}', [CheckoutController::class, 'createRental'])->name('checkout.create-rental');
    Route::post('/rental/checkout/{bike}', [CheckoutController::class, 'storeRental'])->name('checkout.store-rental');
    Route::get('/rental/checkout/{bike}/check', [CheckoutController::class, 'checkAvailability'])->name('checkout.check-rental');
});

//RENTAL AVAILABILITY
Route::get('/bikes/{bike}/availability', [RentalBikeController::class, 'availability'])->name('bikes.availability');

//PROMO BANNER HOMEPAGE
Route::get('/api/promo-banner', [PromoBannerController::class, 'apiShow']);


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

    Route::get('/profile/orders/search', [ProfileController::class, 'searchorders'])->name('profile.orders.search');

    // WISHLIST
    Route::get('/profile/wishlist', [WishlistController::class, 'index'])->name('profile.wishlist.index');

    Route::get('/profile/wishlist/items', [WishlistController::class, 'items'])->name('profile.wishlist.items');

    Route::get('/profile/wishlist/{bike}/status', [WishlistController::class, 'status'])->name('wishlist.status');

    Route::post('/profile/wishlist/{bike}', [WishlistController::class, 'store'])->name('wishlist.store');

    Route::delete('/profile/wishlist/{bike}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    //COMPARE
    Route::get('/profile/compare', [CompareController::class, 'index'])->name('profile.compare.index');

    Route::get('/profile/compare/items', [CompareController::class, 'items'])->name('profile.compare.items');

    Route::get('/profile/compare/status', [CompareController::class, 'status'])->name('profile.compare.status');

    Route::post('/profile/compare/{bike}', [CompareController::class, 'store'])->name('profile.compare.store');

    Route::delete('profile/compare/{bike}', [CompareController::class, 'destroy'])->name('profile.compare.destroy');

    //REVIEWS
    Route::post('/bikes/{bike}/reviews', [ReviewController::class, 'store'])->name('bikes.reviews.store');

    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('bikes.reviews.update');

    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('bikes.reviews.destroy');
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

    Route::post('/dashboard/management/categories/edit-{category}', [StaffmanagementController::class, 'editcategory'])->name('category.edit');

    // --------- ACTIVE RENTALS TRACKING --------- \\
    Route::get('/dashboard/management/activerentals', [StaffmanagementController::class, 'activerentals'])->name('dashboard.management.activerentals');

    Route::get('/dashboard/management/activerentals/filter', [StaffmanagementController::class, 'activerentalsfilter'])->name('activerentals.filter');

    Route::post('/dashboard/management/activerentals/{order}', [StaffmanagementController::class, 'updatereturned'])->name('activerentals.update');

    // --------- ORDERS --------- \\
    Route::get('/dashboard/management/orders', [StaffmanagementController::class, 'manageorders'])->name('dashboard.management.orders');

    Route::post('/dashboard/management/order-update/{id}', [StaffmanagementController::class, 'orderupdate'])->name('order.update');

    Route::get('/dashboard/management/orders/search', [StaffmanagementController::class, 'searchorder'])->name('order.search');

   // --------- HISTORY --------- \\
    Route::get('/dashboard/management/orderhistory', [StaffmanagementController::class, 'history'])->name('dashboard.management.orderhistory');

    Route::get('/dashboard/management/orderhistory/search', [StaffmanagementController::class, 'searchhistory'])->name('orderhistory.search');

    // --------- FEATURED BIKES --------- \\
    Route::get('/dashboard/management/featured-bikes', [FeaturedBikeController::class, 'edit'])->name('featured-bikes.edit');

    Route::put('/dashboard/management/featured-bikes/update', [FeaturedBikeController::class, 'update'])->name('featured-bikes.update');

    Route::get('/dashboard/management/featured-bikes/search', [FeaturedBikeController::class, 'featuredsearch'])->name('featured-bikes.search');
    // --------- PROMO BANNER --------- \\
    Route::get('promo-banner', [PromoBannerController::class, 'index'])->name('dashboard.promo-banner.index');
    Route::get('promo-banner/create', [PromoBannerController::class, 'create'])->name('dashboard.promo-banner.create');
    Route::get('promo-banner/{banner}/edit', [PromoBannerController::class, 'edit'])->name('dashboard.promo-banner.edit');
    Route::post('promo-banner', [PromoBannerController::class, 'store'])->name('dashboard.promo-banner.store');
    Route::put('promo-banner/{banner}', [PromoBannerController::class, 'update'])->name('dashboard.promo-banner.update');
    Route::delete('promo-banner/{banner}', [PromoBannerController::class, 'destroy'])->name('dashboard.promo-banner.destroy');


    // --------- BLOCKED DATES --------- \\
    Route::get('/dashboard/management/blocked-dates', [BlockedDateController::class, 'index'])->name('blocked-dates.index');
    Route::get('/dashboard/management/blocked-dates/events', [BlockedDateController::class, 'events'])->name('blocked-dates.events');
    Route::post('/dashboard/management/blocked-dates', [BlockedDateController::class, 'store'])->name('blocked-dates.store');
    Route::delete('/dashboard/management/blocked-dates/{blockedDate}', [BlockedDateController::class, 'destroy'])->name('blocked-dates.destroy');

    // --------- STATS --------- \\
    Route::get('/dashboard/management/statistics', [StaffmanagementController::class, 'statistics'])->name('dashboard.management.statistics');

    // --------------- REVIEWS --------------- \\
    Route::get('/dashboard/management/reviews', function () {
        return view('staff.reviews.management');
    })->name('dashboard.management.reviews');

    Route::get('/dashboard/management/reviews/items', [StaffmanagementController::class, 'reviews'])->name('dashboard.management.reviews.items');

    Route::delete('/dashboard/management/reviews/{review}', [StaffmanagementController::class, 'deleteReview'])->name('dashboard.management.reviews.delete');

});







//REACT TEST
Route::get('/test/bikes', function () { return view('react'); });
Route::get('/about-us-react', function () { return view('react'); })->name('about-us-react');

/*------------- REACT PAGES -------------*/
Route::get('/current-user', function (Request $request)
{
    return response()->json(['user' => $request->user()?->load('role'),]);
});

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
Route::middleware('auth')->group(function ()
{
    // ACCOUNT


    // WISHLIST


    // MY ORDERS
    Route::get('/profile/myorders', function () { return view('react'); })->name('profile.myorders');

    Route::get('/api/profile/myorders', [OrdersController::class, 'orders']);
    Route::get('/api/profile/myorders/search', [OrdersController::class, 'searchorders']);

    // MY HISTORY
    Route::get('/profile/myhistory', function () { return view('react'); })->name('profile.myhistory');

    Route::get('/api/profile/myhistory', [OrdersController::class, 'history']);
    Route::get('/api/profile/myhistory/search', [OrdersController::class, 'searchhistory']);
});

/******* ADMIN PAGES *******/
Route::middleware(['auth', 'role:staff'])->group(function ()
{
    //* ADMIN - MANAGEMENT *//
    // PRODUCTS
    Route::get('/admin/manage/products', function () { return view('react'); })->name('admin.products');
    Route::get('/admin/manage/products/edit/{id}', function () { return view('react'); })->name('admin.products.edit');
    Route::get('/admin/manage/products/create', function () { return view('react'); })->name('admin.products.create');

    Route::get('/api/admin/manage/products', [ProductController::class, 'management']);
    Route::patch('/api/admin/manage/products/{id}/quantity', [ProductController::class, 'quantity']);
    Route::get('/api/admin/manage/products/edit/{id}', [ProductController::class, 'edit']);
    Route::patch('/api/admin/manage/products/update/{id}', [ProductController::class, 'update']);
    Route::get('/api/admin/manage/products/create', [ProductController::class, 'create']);
    Route::post('/api/admin/manage/products/add', [ProductController::class, 'add']);

    // CALENDAR
    Route::get('/admin/manage/calendar', function () { return view('react'); })->name('admin.calendar');

    // CATEGORIES
    Route::get('/admin/manage/categories', function () { return view('react'); })->name('admin.categories');

    Route::get('/api/admin/manage/categories', [CategoryController::class, 'index']);
    Route::get('/api/admin/manage/categories/search', [CategoryController::class, 'search']);
    Route::patch('/api/admin/manage/categories/{category}/{id}', [CategoryController::class, 'edit']);
    Route::delete('/api/admin/manage/categories/{category}/{id}', [CategoryController::class, 'delete']);
    Route::post('/api/admin/manage/categories/{category}', [CategoryController::class, 'create']);

    // HOMEPAGE
    Route::get('/admin/manage/homepage', function () { return view('react'); })->name('admin.homepage');

    Route::get('/featured-bikes', [HomeController::class, 'apiFeatured']);

    // PENDING ORDERS
    Route::get('/admin/manage/pending-orders', function () { return view('react'); })->name('admin.pendingorders');

    Route::get('/api/admin/manage/pending-orders', [OrderManagementController::class, 'orders']);
    Route::get('/api/admin/manage/pending-orders/search', [OrderManagementController::class, 'search']);
    Route::patch('/api/admin/manage/pending-orders/update/{id}', [OrderManagementController::class, 'update']);

    // USERS
    Route::get('/admin/manage/users', function () { return view('react'); })->name('admin.users');


    //* ADMIN - TRACKING *//
    // ACTIVE RENTALS
    Route::get('/admin/track/activerentals', function () { return view('react'); })->name('admin.activerentals');

    Route::get('/api/admin/track/active-rentals', [ActiveRentalsController::class, 'activerentals']);
    Route::patch('/api/admin/track/active-rentals/{order}', [ActiveRentalsController::class, 'updatereturned']);

    // PAST ORDERS
    Route::get('/admin/track/past-orders', function () { return view('react'); })->name('admin.pastorders');

    Route::get('/api/admin/track/past-orders', [OrderTrackingController::class, 'history']);
    Route::get('/api/admin/track/past-orders/search', [OrderTrackingController::class, 'search']);

    // STATISTICS
    Route::get('/admin/track/statistics', function () { return view('react'); })->name('admin.statistics');

    Route::get('/api/admin/track/statistics', [StatisticsController::class, 'statistics']);
});

require __DIR__.'/auth.php';
