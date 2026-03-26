<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MembershipPlanController;

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

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register/checkout', [PaymentController::class, 'registerCheckout'])
        ->name('register.checkout');

    Route::get('/register/success', [PaymentController::class, 'registerSuccess'])
        ->name('register.success');

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


    /*
    |--------------------------------------------------------------------------
    | Classes (Requires Active Membership)
    |--------------------------------------------------------------------------
    */

    Route::middleware('membership')->group(function () {

        Route::get('/classes', [ClassesController::class, 'index'])
            ->name('classes.index');

        Route::post('/book/{id}', [BookingController::class, 'store'])
            ->name('book.class');

        Route::delete('/cancel/{id}', [BookingController::class, 'destroy'])
            ->name('cancel.booking');

    });


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

Route::middleware(['auth','admin'])->prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', [AdminController::class, 'index'])
        ->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | 🔍 GLOBAL SEARCH
    |--------------------------------------------------------------------------
    */

    Route::get('/search', [AdminController::class, 'search'])
        ->name('admin.search');


    /*
    |--------------------------------------------------------------------------
    | Revenue Export
    |--------------------------------------------------------------------------
    */

    Route::get('/export-revenue', [AdminController::class, 'exportRevenue'])
        ->name('admin.export.revenue');


    /*
    |--------------------------------------------------------------------------
    | QR Check-In Scanner
    |--------------------------------------------------------------------------
    */

    Route::get('/checkin', [AdminController::class, 'checkinPage'])
        ->name('admin.checkin');

    Route::post('/checkin/{email}', [AdminController::class, 'checkinMember'])
        ->name('admin.checkin.process');


    /*
    |--------------------------------------------------------------------------
    | Class Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('classes')->group(function () {

        Route::get('/create', [AdminController::class, 'create'])
            ->name('admin.classes.create');

        Route::post('/', [AdminController::class, 'store'])
            ->name('admin.classes.store');

        Route::get('/{id}/edit', [AdminController::class, 'editClass'])
            ->name('admin.classes.edit');

        Route::patch('/{id}', [AdminController::class, 'updateClass'])
            ->name('admin.classes.update');

        Route::delete('/{id}', [AdminController::class, 'deleteClass'])
            ->name('admin.classes.delete');

        Route::patch('/{id}/cancel', [AdminController::class, 'cancelClass'])
            ->name('admin.classes.cancel');

    });


    /*
    |--------------------------------------------------------------------------
    | Booking Management
    |--------------------------------------------------------------------------
    */

    Route::patch('/bookings/{id}/attendance', [AdminController::class, 'toggleAttendance'])
        ->name('admin.bookings.attendance');

    Route::delete('/bookings/{id}', [AdminController::class, 'removeBooking'])
        ->name('admin.bookings.remove');


    /*
    |--------------------------------------------------------------------------
    | Membership Plans
    |--------------------------------------------------------------------------
    */

    Route::resource('membership-plans', MembershipPlanController::class)
        ->names([
            'index' => 'admin.membership-plans.index',
            'store' => 'admin.membership-plans.store',
            'destroy' => 'admin.membership-plans.destroy',
            'create' => 'admin.membership-plans.create',
            'edit' => 'admin.membership-plans.edit',
            'update' => 'admin.membership-plans.update',
            'show' => 'admin.membership-plans.show'
        ]);


    /*
    |--------------------------------------------------------------------------
    | ✅ USERS (NEW)

    |--------------------------------------------------------------------------
    */

    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])
    ->name('admin.users.delete');

});