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

        $class = FitnessClass::findOrFail($id);

        $existingBooking = Booking::where('user_id', $user->id)
            ->where('fitness_class_id', $id)
            ->first();

        if ($existingBooking) {
            return back()->with('success', 'You have already booked this class.');
        }

        Booking::create([
            'user_id' => $user->id,
            'fitness_class_id' => $id,
        ]);

        return back()->with('success', 'Class booked successfully!');
    }

    // ✅ CANCEL BOOKING METHOD
    public function destroy($id)
    {
        $user = Auth::user();

        $booking = Booking::where('user_id', $user->id)
            ->where('fitness_class_id', $id)
            ->first();

        if (!$booking) {
            return back()->with('success', 'Booking not found.');
        }

        $booking->delete();

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
