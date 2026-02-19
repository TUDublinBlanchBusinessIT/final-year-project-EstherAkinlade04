<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = FitnessClass::withCount('bookings')
            ->with(['bookings.user']);

        // Filters
        if ($status === 'upcoming') {
            $query->where('class_time', '>=', now());
        }

        if ($status === 'past') {
            $query->where('class_time', '<', now());
        }

        if ($status === 'full') {
            $query->havingRaw('bookings_count >= capacity');
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $classes = $query->orderBy('class_time')->paginate(5);

        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalClasses = FitnessClass::count();

        // REAL revenue (paid bookings × class price)
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->with('fitnessClass')
            ->get()
            ->sum(function ($booking) {
                return $booking->fitnessClass->price ?? 0;
            });

        return view('admin.dashboard', compact(
            'classes',
            'totalUsers',
            'totalBookings',
            'totalClasses',
            'totalRevenue'
        ));
    }

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
            'price' => 'required|numeric|min:0'
        ]);

        FitnessClass::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class created successfully.');
    }

    public function removeBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return back()->with('success', 'Member removed.');
    }

    public function toggleAttendance($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->attended = !$booking->attended;
        $booking->save();

        return back()->with('success', 'Attendance updated.');
    }

    public function markAllAttended($id)
    {
        Booking::where('fitness_class_id', $id)
            ->update(['attended' => true]);

        return back()->with('success', 'All marked present.');
    }

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
            fputcsv($file, ['Name', 'Email', 'Payment', 'Attended']);

            foreach ($class->bookings as $booking) {
                fputcsv($file, [
                    $booking->user->name,
                    $booking->user->email,
                    $booking->payment_status,
                    $booking->attended ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
