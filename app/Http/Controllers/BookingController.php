<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FitnessClass;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store($id)
    {
        $user = Auth::user();

        // Check if class exists
        $class = FitnessClass::findOrFail($id);

        // Check if already booked
        $existingBooking = Booking::where('user_id', $user->id)
            ->where('fitness_class_id', $id)
            ->first();

        if ($existingBooking) {
            return back()->with('success', 'You have already booked this class.');
        }

        // Create booking
        Booking::create([
            'user_id' => $user->id,
            'fitness_class_id' => $id,
        ]);

        return back()->with('success', 'Class booked successfully!');
    }
}
