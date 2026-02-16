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

    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = FitnessClass::withCount('bookings')
            ->with('users');

        // 🔎 Search
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // 📊 Status Filter
        if ($status === 'upcoming') {
            $query->where('class_time', '>=', now());
        }

        if ($status === 'completed') {
            $query->where('class_time', '<', now());
        }

        if ($status === 'full') {
            $query->havingRaw('bookings_count >= capacity');
        }

        $classes = $query->orderBy('class_time')
            ->paginate(5)
            ->withQueryString();

        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalClasses = FitnessClass::count();

        return view('admin.dashboard', compact(
            'classes',
            'totalUsers',
            'totalBookings',
            'totalClasses',
            'search',
            'status'
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
        $class = FitnessClass::withCount('bookings')->findOrFail($id);

        // ❌ Prevent editing past classes
        if ($class->class_time < now()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Cannot edit past classes.');
        }

        return view('admin.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $class = FitnessClass::withCount('bookings')->findOrFail($id);

        // ❌ Prevent editing past classes
        if ($class->class_time < now()) {
            return back()->with('error', 'Cannot edit past classes.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'class_time' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        if ($validated['capacity'] < $class->bookings_count) {
            return back()->with('error',
                'Capacity cannot be lower than current bookings (' .
                $class->bookings_count . ').'
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

        // ❌ Prevent deleting past classes
        if ($class->class_time < now()) {
            return back()->with('error', 'Cannot delete past classes.');
        }

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
