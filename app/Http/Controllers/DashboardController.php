<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $bookings = $user->fitnessClasses()
            ->orderBy('class_time')
            ->get();

        return view('dashboard', compact('user', 'bookings'));
    }

    public function history()
    {
        $bookings = Auth::user()
            ->fitnessClasses()
            ->orderBy('class_time')
            ->get();

        return view('bookings.history', compact('bookings'));
    }
}
