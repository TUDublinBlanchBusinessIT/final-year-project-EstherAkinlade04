<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FitnessClass;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Store Booking (Creates UNPAID booking)
    |--------------------------------------------------------------------------
    */

    public function store($id)
    {
        $user = Auth::user();
        $class = FitnessClass::findOrFail($id);

        if ($class->is_cancelled) {
            return back()->with('error', 'This class has been cancelled.');
        }

        if (Carbon::parse($class->class_time)->isPast()) {
            return back()->with('error', 'You cannot book a past class.');
        }

        if ($class->bookings()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You already booked this class.');
        }

        if ($class->bookings()->count() >= $class->capacity) {
            return back()->with('error', 'This class is full.');
        }

        Booking::create([
            'user_id' => $user->id,
            'fitness_class_id' => $class->id,
            'payment_status' => 'unpaid'
        ]);

        return back()->with('success', 'Class reserved! Please complete payment.');
    }

    /*
    |--------------------------------------------------------------------------
    | Simulated Payment
    |--------------------------------------------------------------------------
    */

    public function pay($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->payment_status === 'paid') {
            return back()->with('error', 'Already paid.');
        }

        // 🔥 Simulate Stripe success
        $booking->update([
            'payment_status' => 'paid'
        ]);

        return back()->with('success', 'Payment successful! 🎉');
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Booking
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('fitness_class_id', $id)
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found.');
        }

        $booking->delete();

        return back()->with('success', 'Booking cancelled.');
    }
}
