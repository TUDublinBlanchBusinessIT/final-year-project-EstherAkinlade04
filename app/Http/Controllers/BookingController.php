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

        // 🔒 Prevent duplicate booking
        if ($class->bookings()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already booked this class.');
        }

        // 🔴 Capacity check (LIVE count from DB)
        $currentBookings = $class->bookings()->count();

        if ($currentBookings >= $class->capacity) {
            return back()->with('error', 'Sorry, this class is fully booked.');
        }

        // ✅ Create booking
        Booking::create([
            'user_id' => $user->id,
            'fitness_class_id' => $class->id,
        ]);

        return back()->with('success', 'Class booked successfully!');
    }

    // ✅ Cancel Booking
    public function destroy($id)
    {
        $user = Auth::user();

        $booking = Booking::where('user_id', $user->id)
            ->where('fitness_class_id', $id)
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found.');
        }

        $booking->delete();

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
