<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get classes user has booked
        $bookings = $user->fitnessClasses()->orderBy('class_time')->get();

        return view('dashboard', compact('user', 'bookings'));
    }
}
