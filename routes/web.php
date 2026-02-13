<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\BookingController;

// Home page (public)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest routes
Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
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
});
