<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;

class ClassesController extends Controller
{
    public function index()
    {
        // Load bookings with each class (prevents N+1 query problem)
        $classes = FitnessClass::with('bookings')
            ->orderBy('class_time')
            ->get();

        return view('classes.index', compact('classes'));
    }
}
