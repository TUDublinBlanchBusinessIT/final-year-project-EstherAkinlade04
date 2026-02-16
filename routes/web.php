<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/my-bookings', [DashboardController::class, 'history'])
        ->name('bookings.history');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/classes', [ClassesController::class, 'index'])
        ->name('classes.index');

    Route::post('/book/{id}', [BookingController::class, 'store'])
        ->name('book.class');

    Route::delete('/cancel/{id}', [BookingController::class, 'destroy'])
        ->name('cancel.booking');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/classes/create', [AdminController::class, 'create'])
        ->name('admin.classes.create');

    Route::post('/admin/classes', [AdminController::class, 'store'])
        ->name('admin.classes.store');

    Route::get('/admin/classes/{id}/edit', [AdminController::class, 'edit'])
        ->name('admin.classes.edit');

    Route::put('/admin/classes/{id}', [AdminController::class, 'update'])
        ->name('admin.classes.update');

    Route::delete('/admin/classes/{id}', [AdminController::class, 'destroy'])
        ->name('admin.classes.destroy');
});
