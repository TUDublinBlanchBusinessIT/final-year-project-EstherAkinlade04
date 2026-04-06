<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $bookings = $user->fitnessClasses()
            ->orderBy('class_time')
            ->get();

        $daysLeft = null;

        if ($user->end_date) {
            $daysLeft = Carbon::now()->diffInDays($user->end_date, false);
        }

        return view('dashboard', compact('user', 'bookings', 'daysLeft'));
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