<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Home page (smart redirect)
Route::get('/', function () {
    // If user is logged in, send them to dashboard
    if (auth()->check()) {
        return redirect('/dashboard');
    }

    // Otherwise, show public home page
    return view('welcome');
});

// Register
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// Dashboard (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');

// Logout
Route::post('/logout', [AuthController::class, 'logout']);
