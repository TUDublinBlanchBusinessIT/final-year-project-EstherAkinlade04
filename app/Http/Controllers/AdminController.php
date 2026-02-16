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
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $classes = FitnessClass::withCount('bookings')
            ->with(['bookings.user'])
            ->orderBy('class_time')
            ->paginate(5);

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
            'class_time' => 'required|date|after:now',
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
        $class = FitnessClass::findOrFail($id);

        return view('admin.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $class = FitnessClass::withCount('bookings')->findOrFail($id);

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
        $class = FitnessClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Member From Class
    |--------------------------------------------------------------------------
    */

    public function removeBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Member removed successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Attendance
    |--------------------------------------------------------------------------
    */

    public function toggleAttendance($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->attended = !$booking->attended;
        $booking->save();

        return back()->with('success', 'Attendance updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Export Class Attendees to CSV
    |--------------------------------------------------------------------------
    */

    public function exportCsv($id)
    {
        $class = FitnessClass::with('bookings.user')->findOrFail($id);

        $filename = 'class_'.$class->id.'_attendees.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($class) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Name', 'Email', 'Payment Status', 'Attended']);

            foreach ($class->bookings as $booking) {
                fputcsv($file, [
                    $booking->user->name,
                    $booking->user->email,
                    $booking->payment_status ?? 'paid',
                    $booking->attended ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
