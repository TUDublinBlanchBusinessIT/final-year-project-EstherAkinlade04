<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function index()
    {
        $classes = FitnessClass::withCount('bookings')
            ->with(['bookings.user'])
            ->orderBy('class_time')
            ->paginate(5);

        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalClasses = FitnessClass::count();

        // Revenue
        $classRevenue = Booking::where('payment_status', 'paid')
            ->join('fitness_classes', 'bookings.fitness_class_id', '=', 'fitness_classes.id')
            ->sum('fitness_classes.price');

        $membershipRevenue = User::sum('price_paid');
        $totalRevenue = $classRevenue + $membershipRevenue;

        // Monthly Membership Revenue Chart Data
        $monthlyRevenue = User::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(price_paid) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Membership Breakdown
        $membershipBreakdown = User::select(
                'membership_type',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('membership_type')
            ->get();

        $activeMembers = User::where('end_date', '>=', now())->count();
        $expiredMembers = User::where('end_date', '<', now())->count();

        return view('admin.dashboard', compact(
            'classes',
            'totalUsers',
            'totalBookings',
            'totalClasses',
            'totalRevenue',
            'classRevenue',
            'membershipRevenue',
            'monthlyRevenue',
            'membershipBreakdown',
            'activeMembers',
            'expiredMembers'
        ));
    }

    // 🔥 CSV EXPORT
    public function exportRevenue()
    {
        $users = User::select('name', 'email', 'membership_type', 'price_paid', 'created_at')->get();

        $response = new StreamedResponse(function () use ($users) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Name', 'Email', 'Membership Type', 'Amount Paid', 'Date']);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->name,
                    $user->email,
                    $user->membership_type,
                    $user->price_paid,
                    $user->created_at
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="revenue.csv"');

        return $response;
    }
}