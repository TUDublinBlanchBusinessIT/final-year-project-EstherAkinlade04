<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = FitnessClass::withCount('bookings')
            ->with(['bookings.user'])
            ->orderBy('class_time');

        $classes = $query->paginate(5);

        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalClasses = FitnessClass::count();

        // Optimised revenue calculation
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->join('fitness_classes', 'bookings.fitness_class_id', '=', 'fitness_classes.id')
            ->sum('fitness_classes.price');

        return view('admin.dashboard', compact(
            'classes',
            'totalUsers',
            'totalBookings',
            'totalClasses',
            'totalRevenue'
        ));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'class_time' => 'required|date|after:now',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string'
        ]);

        FitnessClass::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class created successfully.');
    }

    public function cancelClass($id)
    {
        FitnessClass::findOrFail($id)
            ->update(['is_cancelled' => true]);

        return back()->with('success', 'Class cancelled.');
    }

    public function removeBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return back()->with('success', 'Member removed.');
    }

    public function toggleAttendance($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->attended = !$booking->attended;
        $booking->save();

        return back()->with('success', 'Attendance updated.');
    }
}