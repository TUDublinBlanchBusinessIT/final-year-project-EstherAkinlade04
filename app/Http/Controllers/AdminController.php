<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Admin Dashboard
    public function index()
    {
        $classes = FitnessClass::withCount('bookings')
            ->orderBy('class_time')
            ->get();

        return view('admin.dashboard', compact('classes'));
    }

    // Show Create Class Form
    public function create()
    {
        return view('admin.create');
    }

    // Store New Class
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'class_time' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        FitnessClass::create([
            'name' => $request->name,
            'description' => $request->description,
            'class_time' => $request->class_time,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class created successfully!');
    }
}
