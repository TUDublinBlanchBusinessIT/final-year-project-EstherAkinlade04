<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Guest (Registration + Login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Registration Page
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    // Stripe Registration Checkout
    Route::post('/register/checkout', [PaymentController::class, 'registerCheckout'])
        ->name('register.checkout');

    // Registration Success (after Stripe)
    Route::get('/register/success', [PaymentController::class, 'registerSuccess'])
        ->name('register.success');

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});


/*
|--------------------------------------------------------------------------
| Authenticated Users
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/classes', [ClassesController::class, 'index'])
        ->name('classes.index');

    Route::post('/book/{id}', [BookingController::class, 'store'])
        ->name('book.class');

    Route::delete('/cancel/{id}', [BookingController::class, 'destroy'])
        ->name('cancel.booking');

    /*
    |--------------------------------------------------------------------------
    | Membership Renewal
    |--------------------------------------------------------------------------
    */

    Route::get('/checkout', [PaymentController::class, 'checkout'])
        ->name('checkout');

    Route::get('/payment-success', [PaymentController::class, 'success'])
        ->name('payment.success');

    Route::get('/payment-cancel', [PaymentController::class, 'cancel'])
        ->name('payment.cancel');
});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // Revenue Export
    Route::get('/export-revenue', [AdminController::class, 'exportRevenue'])
        ->name('admin.export.revenue');

    // Classes
    Route::get('/classes/create', [AdminController::class, 'create'])
        ->name('admin.classes.create');

    Route::post('/classes', [AdminController::class, 'store'])
        ->name('admin.classes.store');

    Route::patch('/classes/{id}/cancel', [AdminController::class, 'cancelClass'])
        ->name('admin.classes.cancel');

    // Bookings
    Route::patch('/bookings/{id}/attendance', [AdminController::class, 'toggleAttendance'])
        ->name('admin.bookings.attendance');

    Route::delete('/bookings/{id}', [AdminController::class, 'removeBooking'])
        ->name('admin.bookings.remove');

});