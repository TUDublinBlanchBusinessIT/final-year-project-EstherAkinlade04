<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

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
| Guest
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


/*
|--------------------------------------------------------------------------
| Authenticated Users
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/classes', [ClassesController::class, 'index'])->name('classes.index');

    // BOOK (creates unpaid booking first)
    Route::post('/book/{id}', [BookingController::class, 'store'])->name('book.class');

    // 💳 PAYMENT SIMULATION
    Route::post('/pay/{booking}', [BookingController::class, 'pay'])->name('booking.pay');

    Route::delete('/cancel/{id}', [BookingController::class, 'destroy'])->name('cancel.booking');
});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/classes/create', [AdminController::class, 'create'])
        ->name('admin.classes.create');

    Route::post('/admin/classes', [AdminController::class, 'store'])
        ->name('admin.classes.store');

    Route::patch('/admin/classes/{id}/cancel', [AdminController::class, 'cancelClass'])
        ->name('admin.classes.cancel');

    Route::patch('/admin/bookings/{id}/attendance', [AdminController::class, 'toggleAttendance'])
        ->name('admin.bookings.attendance');

    Route::delete('/admin/bookings/{id}', [AdminController::class, 'removeBooking'])
        ->name('admin.bookings.remove');

    Route::patch('/admin/classes/{id}/mark-all', [AdminController::class, 'markAllAttended'])
        ->name('admin.classes.markAll');

    Route::get('/admin/classes/{id}/export', [AdminController::class, 'exportCsv'])
        ->name('admin.classes.export');
});
