<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $classes = FitnessClass::withCount('bookings')
            ->with('users')
            ->orderBy('class_time')
            ->get();

        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalClasses = FitnessClass::count();

        return view('admin.dashboard', compact(
            'classes',
            'totalUsers',
            'totalBookings',
            'totalClasses'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Class
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'class_time' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        FitnessClass::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Class
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $class = FitnessClass::findOrFail($id);
        return view('admin.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $class = FitnessClass::withCount('bookings')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'class_time' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        // 🔒 Prevent reducing capacity below current bookings
        if ($validated['capacity'] < $class->bookings_count) {
            return back()->with('error',
                'Capacity cannot be lower than current bookings ('
                . $class->bookings_count . ').'
            );
        }

        $class->update($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Class
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $class = FitnessClass::withCount('bookings')->findOrFail($id);

        // 🔒 Prevent deleting class if members are booked
        if ($class->bookings_count > 0) {
            return back()->with('error',
                'Cannot delete class. Members are currently booked.'
            );
        }

        $class->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class deleted successfully.');
    }
}
