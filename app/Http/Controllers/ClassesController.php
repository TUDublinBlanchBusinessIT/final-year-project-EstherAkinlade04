<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = FitnessClass::withCount('bookings')
            ->with(['bookings' => function ($query) {
                $query->select('id', 'user_id', 'fitness_class_id', 'payment_status');
            }])
            ->orderBy('class_time')
            ->get();

        return view('classes.index', compact('classes'));
    }
}
