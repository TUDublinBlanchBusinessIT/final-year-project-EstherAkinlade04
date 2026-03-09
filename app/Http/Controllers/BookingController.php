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
    | Store Booking
    |--------------------------------------------------------------------------
    */

    public function store($id)
    {
        $user = Auth::user();
        $class = FitnessClass::findOrFail($id);

        // 🚫 Membership expired
        if (!$user->end_date || Carbon::parse($user->end_date)->isPast()) {
            return back()->with('error', 'Your membership has expired. Please renew to book classes.');
        }

        // 🚫 Class cancelled
        if ($class->is_cancelled) {
            return back()->with('error', 'This class has been cancelled.');
        }

        // 🚫 Past class
        if (Carbon::parse($class->class_time)->isPast()) {
            return back()->with('error', 'You cannot book a past class.');
        }

        // 🚫 Already booked
        if ($class->bookings()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You already booked this class.');
        }

        // 🚫 Class full
        if ($class->bookings()->count() >= $class->capacity) {
            return back()->with('error', 'This class is full.');
        }

        Booking::create([
            'user_id' => $user->id,
            'fitness_class_id' => $class->id,
            'payment_status' => 'paid',
        ]);

        return back()->with('success', 'Class booked successfully!');
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

        $class = FitnessClass::find($id);

        if (!$class) {
            return back()->with('error', 'Class not found.');
        }

        // 🚫 Prevent cancelling past classes
        if (Carbon::parse($class->class_time)->isPast()) {
            return back()->with('error', 'You cannot cancel a completed class.');
        }

        $booking->delete();

        return back()->with('success', 'Booking cancelled successfully.');
    }
}