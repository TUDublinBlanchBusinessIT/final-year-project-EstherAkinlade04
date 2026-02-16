<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;

class AdminController extends Controller
{
    public function index()
    {
        $classes = FitnessClass::withCount('bookings')
            ->orderBy('class_time')
            ->get();

        return view('admin.dashboard', compact('classes'));
    }
}
