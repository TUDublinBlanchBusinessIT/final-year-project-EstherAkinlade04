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
    | Store Booking (WITH PAYMENT SIMULATION)
    |--------------------------------------------------------------------------
    */

    public function store($id)
    {
        $user = Auth::user();
        $class = FitnessClass::findOrFail($id);

        // 🚫 Prevent booking cancelled class
        if ($class->is_cancelled) {
            return back()->with('error', 'This class has been cancelled.');
        }

        // 🔴 Prevent booking past classes
        if (Carbon::parse($class->class_time)->isPast()) {
            return back()->with('error', 'You cannot book a past class.');
        }

        // 🔒 Prevent duplicate booking
        if ($class->bookings()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already booked this class.');
        }

        // 🔴 Capacity check
        if ($class->bookings()->count() >= $class->capacity) {
            return back()->with('error', 'Sorry, this class is fully booked.');
        }

        /*
        |--------------------------------------------------------------------------
        | 💳 PAYMENT SIMULATION
        |--------------------------------------------------------------------------
        | For now:
        | - Payment always succeeds
        | - Status automatically becomes "paid"
        */

        $paymentStatus = 'paid';

        Booking::create([
            'user_id' => $user->id,
            'fitness_class_id' => $class->id,
            'payment_status' => $paymentStatus,
            'attended' => false,
        ]);

        return back()->with('success', 'Payment successful! Class booked 🎉');
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Booking
    |--------------------------------------------------------------------------
    */

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
