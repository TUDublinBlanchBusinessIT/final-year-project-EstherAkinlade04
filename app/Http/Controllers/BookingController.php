<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store($id)
    {
        // We will add logic in Step 2
        return back()->with('success', 'Booking request received.');
    }
}
