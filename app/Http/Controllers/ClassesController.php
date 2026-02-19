<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = FitnessClass::withCount('bookings')
            ->where('is_cancelled', false)
            ->where('class_time', '>=', now())
            ->orderBy('class_time')
            ->get();

        return view('classes.index', compact('classes'));
    }
}
