<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gym;

class GymController extends Controller
{
    public function switch($gymId)
    {
        session(['selected_gym_id' => $gymId]);

        return back()->with('success', 'Gym switched successfully');
    }
}