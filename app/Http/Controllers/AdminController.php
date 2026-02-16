<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard
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

    // Show Create Form
    public function create()
    {
        return view('admin.create');
    }

    // Store Class
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'class_time' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        FitnessClass::create($request->all());

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class created successfully.');
    }

    // Show Edit Form
    public function edit($id)
    {
        $class = FitnessClass::findOrFail($id);
        return view('admin.edit', compact('class'));
    }

    // Update Class
    public function update(Request $request, $id)
    {
        $class = FitnessClass::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'class_time' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        $class->update($request->all());

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class updated successfully.');
    }

    // Delete Class
    public function destroy($id)
    {
        $class = FitnessClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class deleted successfully.');
    }
}
