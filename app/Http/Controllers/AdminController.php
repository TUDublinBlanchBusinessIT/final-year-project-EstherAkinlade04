<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;

class AdminController extends Controller
{
    public function index()
    {
        // Load classes with booking count
        $classes = FitnessClass::withCount('bookings')->get();

        return view('admin.index', compact('classes'));
    }
}
