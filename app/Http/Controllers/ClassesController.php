<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class ClassesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ✅ SELECTED GYM
        $gymId = session('selected_gym_id');

        /*
        |--------------------------------------------------------------------------
        | ALL CLASSES
        |--------------------------------------------------------------------------
        */

        $classes = FitnessClass::withCount('bookings')
            ->with(['bookings' => function ($query) {
                $query->select('id', 'user_id', 'fitness_class_id', 'payment_status');
            }])
            ->when($gymId, function ($query) use ($gymId) {
                $query->where('gym_id', $gymId);
            })
            ->orderBy('class_time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | USER BOOKINGS
        |--------------------------------------------------------------------------
        */

        $userBookings = Booking::where('user_id', $user->id)
            ->with('fitnessClass')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | EXTRACT PREFERENCES (CLASS NAMES)
        |--------------------------------------------------------------------------
        */

        $keywords = $userBookings
            ->pluck('fitnessClass.name')
            ->filter()
            ->unique();

        /*
        |--------------------------------------------------------------------------
        | SMART RECOMMENDATIONS
        |--------------------------------------------------------------------------
        */

        $recommendedClasses = collect();

        if ($keywords->count()) {

            $recommendedClasses = FitnessClass::when($gymId, function ($query) use ($gymId) {
                    $query->where('gym_id', $gymId);
                })
                ->where('class_time', '>', now()) // only future classes
                ->whereNotIn('id', $userBookings->pluck('fitness_class_id')) // exclude booked
                ->where(function ($query) use ($keywords) {
                    foreach ($keywords as $word) {
                        $query->orWhere('name', 'like', "%$word%");
                    }
                })
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK (POPULAR CLASSES)
        |--------------------------------------------------------------------------
        */

        if ($recommendedClasses->isEmpty()) {

            $recommendedClasses = FitnessClass::withCount('bookings')
                ->when($gymId, function ($query) use ($gymId) {
                    $query->where('gym_id', $gymId);
                })
                ->where('class_time', '>', now())
                ->orderByDesc('bookings_count')
                ->take(5)
                ->get();
        }

        return view('classes.index', compact('classes', 'recommendedClasses'));
    }
}