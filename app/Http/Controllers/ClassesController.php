<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\Booking;

class ClassesController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ✅ GET SELECTED GYM
        $gymId = session('selected_gym_id');

        // 📅 EXISTING CLASSES (UNCHANGED + GYM FILTER)
        $classes = FitnessClass::withCount('bookings')
            ->with(['bookings' => function ($query) {
                $query->select('id', 'user_id', 'fitness_class_id', 'payment_status');
            }])
            ->when($gymId, function ($query) use ($gymId) {
                $query->where('gym_id', $gymId);
            })
            ->orderBy('class_time')
            ->get();

        // 🧠 USER BOOKINGS
        $userBookings = Booking::where('user_id', $user->id)
            ->with('fitnessClass')
            ->get();

        // 🧠 EXTRACT CLASS NAMES (KEYWORDS)
        $keywords = $userBookings
            ->pluck('fitnessClass.name')
            ->filter()
            ->unique();

        // 🧠 RECOMMENDED CLASSES (SAME GYM)
        $recommendedClasses = FitnessClass::when($gymId, function ($query) use ($gymId) {
                $query->where('gym_id', $gymId);
            })
            ->when($keywords->count(), function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->orWhere('name', 'like', "%$word%");
                }
            })
            ->whereNotIn('id', $userBookings->pluck('fitness_class_id'))
            ->take(5)
            ->get();

        return view('classes.index', compact('classes', 'recommendedClasses'));
    }
}