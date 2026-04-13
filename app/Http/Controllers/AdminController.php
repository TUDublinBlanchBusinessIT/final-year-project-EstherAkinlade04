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
        $gymId = auth()->user()->gym_id;

        /* =========================
         | 📅 CLASSES
         ========================= */
        $classes = FitnessClass::withCount('bookings')
            ->with('bookings.user')
            ->where('gym_id', $gymId)
            ->orderBy('class_time')
            ->paginate(5);

        /* =========================
         | 📊 BASIC STATS
         ========================= */
        $totalUsers = User::where('gym_id', $gymId)->count();

        $totalBookings = Booking::whereHas('fitnessClass', fn($q) =>
            $q->where('gym_id', $gymId)
        )->count();

        $totalClasses = FitnessClass::where('gym_id', $gymId)->count();

        /* =========================
         | 💰 REVENUE
         ========================= */
        $classRevenue = Booking::where('payment_status', 'paid')
            ->whereHas('fitnessClass', fn($q) =>
                $q->where('gym_id', $gymId)
            )
            ->join('fitness_classes', 'bookings.fitness_class_id', '=', 'fitness_classes.id')
            ->sum('fitness_classes.price');

        $membershipRevenue = User::where('gym_id', $gymId)->sum('price_paid');
        $totalRevenue = $classRevenue + $membershipRevenue;

        /* =========================
         | 📈 MONTHLY REVENUE
         ========================= */
        $monthlyRevenue = User::where('gym_id', $gymId)
            ->select(
                DB::raw('MONTH(created_at) as month_number'),
                DB::raw('MONTHNAME(created_at) as month'),
                DB::raw('SUM(price_paid) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month_number', 'month')
            ->orderBy('month_number')
            ->get();

        /* =========================
         | 📊 MEMBERSHIP BREAKDOWN
         ========================= */
        $membershipBreakdown = User::where('gym_id', $gymId)
            ->select('membership_type', DB::raw('COUNT(*) as total'))
            ->groupBy('membership_type')
            ->get();

        /* =========================
         | 👥 MEMBERS
         ========================= */
        $activeMembers = User::where('gym_id', $gymId)
            ->where('end_date', '>=', now())
            ->count();

        $expiredMembers = User::where('gym_id', $gymId)
            ->where('end_date', '<', now())
            ->count();

        $expiringSoonUsers = User::where('gym_id', $gymId)
            ->whereBetween('end_date', [now(), now()->addDays(7)])
            ->orderBy('end_date')
            ->get(['name', 'end_date']);

        /* =========================
         | ❌ CANCELLED CLASSES
         ========================= */
        $cancelledClasses = FitnessClass::where('gym_id', $gymId)
            ->where('is_cancelled', true)
            ->orderBy('class_time')
            ->get(['name', 'class_time']);

        /* =========================
         | 💰 REVENUE PER CLASS
         ========================= */
        $classRevenueData = FitnessClass::where('gym_id', $gymId)
            ->withCount('bookings')
            ->get()
            ->map(fn($class) => [
                'name' => $class->name,
                'revenue' => $class->bookings_count * $class->price
            ]);

        /* =========================
         | 🔥 HEATMAP DATA
         ========================= */
        $activityData = Booking::whereHas('fitnessClass', fn($q) =>
                $q->where('gym_id', $gymId)
            )
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($d) => [
                'date' => $d->date,
                'total' => (int) $d->total
            ]);

        /* =========================
         | 📊 BOOKING CHART
         ========================= */
        $bookingChart = FitnessClass::where('gym_id', $gymId)
            ->withCount('bookings')
            ->get();

        $bookingLabels = $bookingChart->pluck('name');
        $bookingCounts = $bookingChart->pluck('bookings_count');

        /* =========================
         | 🔥 POPULAR CLASS
         ========================= */
        $mostPopularClass = FitnessClass::where('gym_id', $gymId)
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->first();

        /* =========================
         | 🏆 TOP MEMBERS
         ========================= */
        $topMembers = User::where('gym_id', $gymId)
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        /* =========================
         | 🚨 ALERTS
         ========================= */
        $lowBookingClasses = FitnessClass::where('gym_id', $gymId)
            ->withCount('bookings')
            ->having('bookings_count', '<', 3)
            ->get();

        $almostFullClasses = FitnessClass::where('gym_id', $gymId)
            ->withCount('bookings')
            ->get()
            ->filter(fn($c) =>
                $c->capacity > 0 &&
                ($c->bookings_count / $c->capacity) >= 0.8
            );

        /* =========================
         | ⏰ PEAK TIME
         ========================= */
        $peakTime = Booking::whereHas('fitnessClass', fn($q) =>
                $q->where('gym_id', $gymId)
            )
            ->select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('day', 'hour')
            ->orderByDesc('total')
            ->first();

        /* =========================
         | 📊 PERFORMANCE
         ========================= */
        $classPerformance = FitnessClass::where('gym_id', $gymId)
            ->withCount('bookings')
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'score' => $c->capacity > 0
                    ? round(($c->bookings_count / $c->capacity) * 100)
                    : 0
            ]);

        /* =========================
         | 📈 GROWTH
         ========================= */
        $lastMonthRevenue = User::where('gym_id', $gymId)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('price_paid');

        $thisMonthRevenue = User::where('gym_id', $gymId)
            ->whereMonth('created_at', now()->month)
            ->sum('price_paid');

        $growthRate = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100)
            : 100;

        /* =========================
         | 🧠 INSIGHTS
         ========================= */
        $insights = [];

        if ($activeMembers < $expiredMembers) {
            $insights[] = "⚠️ Retention is dropping";
        }

        if ($totalBookings < 20) {
            $insights[] = "📉 Low booking activity";
        }

        if ($mostPopularClass) {
            $insights[] = "🔥 {$mostPopularClass->name} is trending";
        }

        $insights[] = $growthRate > 0
            ? "📈 Revenue growing {$growthRate}%"
            : "📉 Revenue declined {$growthRate}%";

        /* =========================
         | 👥 USERS
         ========================= */
        $users = User::where('gym_id', $gymId)
            ->withCount('bookings')
            ->latest()
            ->take(20)
            ->get();

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
            'expiredMembers',
            'expiringSoonUsers',
            'cancelledClasses',
            'classRevenueData',
            'activityData',
            'bookingLabels',
            'bookingCounts',
            'mostPopularClass',
            'topMembers',
            'lowBookingClasses',
            'almostFullClasses',
            'peakTime',
            'classPerformance',
            'growthRate',
            'insights',
            'users'
        ));
    }

    /* =========================
     | STORE CLASS
     ========================= */
    public function store(Request $request)
    {
        $gymId = auth()->user()->gym_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'class_time' => 'required|date',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string'
        ]);

        $validated['gym_id'] = $gymId;

        FitnessClass::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Class created successfully');
    }

    /* =========================
     | SEARCH (FIXED)
     ========================= */
    public function search(Request $request)
    {
        $query = $request->q;
        $gymId = auth()->user()->gym_id;

        if (!$query) {
            return response()->json([
                'users' => [],
                'classes' => [],
                'bookings' => []
            ]);
        }

        return response()->json([
            'users' => User::where('gym_id', $gymId)
                ->where(fn($q) =>
                    $q->where('name', 'like', "%$query%")
                      ->orWhere('email', 'like', "%$query%")
                )
                ->limit(5)
                ->get(),

            'classes' => FitnessClass::where('gym_id', $gymId)
                ->where('name', 'like', "%$query%")
                ->limit(5)
                ->get(),

            'bookings' => Booking::with(['user','fitnessClass'])
                ->whereHas('fitnessClass', fn($q) =>
                    $q->where('gym_id', $gymId)
                )
                ->limit(5)
                ->get()
        ]);
    }

    /* =========================
     | SIMPLE ACTIONS
     ========================= */
    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted');
    }

    public function editClass($id)
    {
        $class = FitnessClass::findOrFail($id);
        return view('admin.edit-class', compact('class'));
    }

    public function updateClass(Request $request, $id)
    {
        FitnessClass::findOrFail($id)->update([
            'admin_notes' => $request->admin_notes
        ]);

        return back()->with('success', 'Class notes updated');
    }

    public function deleteClass($id)
    {
        FitnessClass::findOrFail($id)->delete();
        return back()->with('success', 'Class deleted');
    }

    public function cancelClass($id)
    {
        FitnessClass::findOrFail($id)->update(['is_cancelled' => true]);
        return back()->with('success', 'Class cancelled');
    }

    public function toggleAttendance($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['attended' => !$booking->attended]);
        return back();
    }

    public function removeBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return back()->with('success', 'Booking removed');
    }

    public function checkinPage()
    {
        return view('admin.checkin');
    }

    /* =========================
     | EXPORT CSV
     ========================= */
    public function exportRevenue()
    {
        $gymId = auth()->user()->gym_id;

        $users = User::where('gym_id', $gymId)
            ->select('name','email','membership_type','price_paid','created_at')
            ->get();

        $response = new StreamedResponse(function () use ($users) {

            $handle = fopen('php://output','w');

            fputcsv($handle, ['Name','Email','Membership Type','Amount Paid (€)','Date']);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->name,
                    $user->email,
                    $user->membership_type,
                    $user->price_paid,
                    $user->created_at->format('Y-m-d')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type','text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="vault_revenue_report.csv"'
        );

        return $response;
    }
}