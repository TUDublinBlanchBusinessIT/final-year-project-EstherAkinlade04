<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $classes = FitnessClass::withCount('bookings')
            ->with(['bookings.user'])
            ->orderBy('class_time')
            ->paginate(5);

        return view('admin.dashboard', [
            'classes' => $classes,
            'totalUsers' => User::count(),
            'totalBookings' => Booking::count(),
            'totalClasses' => FitnessClass::count(),
        ]);
    }

    public function removeBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return back()->with('success', 'Member removed successfully.');
    }

    public function toggleAttendance($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->attended = !$booking->attended;
        $booking->save();

        return back()->with('success', 'Attendance updated.');
    }

    public function markAllAttended($classId)
    {
        Booking::where('fitness_class_id', $classId)
            ->update(['attended' => true]);

        return back()->with('success', 'All attendees marked as attended.');
    }

    public function exportCsv($id)
    {
        $class = FitnessClass::with('bookings.user')->findOrFail($id);

        $filename = "class_{$class->id}_attendees.csv";

        return response()->stream(function () use ($class) {

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

        }, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ]);
    }
}
