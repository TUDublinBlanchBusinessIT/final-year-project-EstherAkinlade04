<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Booking;          // ✅ ADDED
use App\Models\FitnessClass;     // ✅ ADDED

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

        // 🧠 ✅ ADDED: Recommendation System

        $favoriteClassIds = Booking::where('user_id', $user->id)
            ->select('fitness_class_id')
            ->groupBy('fitness_class_id')
            ->orderByRaw('COUNT(*) DESC')
            ->pluck('fitness_class_id');

        if ($favoriteClassIds->isNotEmpty()) {

            $bookedIds = Booking::where('user_id', $user->id)
                ->pluck('fitness_class_id');

            $recommendedClasses = FitnessClass::whereIn('id', $favoriteClassIds)
                ->whereNotIn('id', $bookedIds)
                ->take(3)
                ->get();

        } else {

            // fallback for new users
            $recommendedClasses = FitnessClass::latest()
                ->take(3)
                ->get();
        }

        return view('dashboard', compact('user', 'bookings', 'daysLeft', 'recommendedClasses')); // ✅ UPDATED
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