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
            ->withPivot('payment_status', 'created_at')
            ->orderBy('class_time')
            ->get();

        $upcoming = $bookings->where('class_time', '>=', now());
        $past = $bookings->where('class_time', '<', now());

        $totalSpent = $bookings
            ->where('pivot.payment_status', 'paid')
            ->sum('price');

        return view('dashboard', compact(
            'user',
            'bookings',
            'upcoming',
            'past',
            'totalSpent'
        ));
    }
}