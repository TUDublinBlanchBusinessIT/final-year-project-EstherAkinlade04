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
        // 📅 Classes
        $classes = FitnessClass::withCount('bookings')
            ->with(['bookings.user'])
            ->orderBy('class_time')
            ->paginate(5);

        // 📊 Basic stats
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalClasses = FitnessClass::count();

        // 💰 Revenue
        $classRevenue = Booking::where('payment_status','paid')
            ->join('fitness_classes','bookings.fitness_class_id','=','fitness_classes.id')
            ->sum('fitness_classes.price');

        $membershipRevenue = User::sum('price_paid');
        $totalRevenue = $classRevenue + $membershipRevenue;

        // 📈 Monthly revenue
        $monthlyRevenue = User::select(
                DB::raw('MONTH(created_at) as month_number'),
                DB::raw('MONTHNAME(created_at) as month'),
                DB::raw('SUM(price_paid) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month_number','month')
            ->orderBy('month_number')
            ->get();

        // 📊 Membership breakdown
        $membershipBreakdown = User::select(
                'membership_type',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('membership_type')
            ->get();

        // 👥 Member status
        $activeMembers = User::where('end_date','>=',now())->count();
        $expiredMembers = User::where('end_date','<',now())->count();

        // ⏳ Expiring soon
        $expiringSoonUsers = User::whereBetween('end_date',[now(), now()->addDays(7)])
            ->orderBy('end_date')
            ->get(['name','end_date']);

        // ❌ Cancelled classes
        $cancelledClasses = FitnessClass::where('is_cancelled', true)
            ->orderBy('class_time')
            ->get(['name','class_time']);

        // 💰 Revenue per class
        $classRevenueData = FitnessClass::withCount('bookings')
            ->get()
            ->map(fn($class) => [
                'name' => $class->name,
                'revenue' => $class->bookings_count * $class->price
            ]);

        // 🔥 HEATMAP (FIXED)
        $activityData = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($d) => [
                'date' => $d->date,
                'total' => (int)$d->total
            ])
            ->values();

        // 📊 Booking chart
        $bookingChart = FitnessClass::withCount('bookings')->get();
        $bookingLabels = $bookingChart->pluck('name');
        $bookingCounts = $bookingChart->pluck('bookings_count');

        // 🔥 Most popular class
        $mostPopularClass = FitnessClass::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->first();

        // 🏆 Top members
        $topMembers = User::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        // 🚨 LOW BOOKING ALERT
        $lowBookingClasses = FitnessClass::withCount('bookings')
            ->having('bookings_count','<',3)
            ->get();

        // 🔥 ALMOST FULL
        $almostFullClasses = FitnessClass::withCount('bookings')
            ->get()
            ->filter(fn($c) =>
                $c->capacity > 0 &&
                ($c->bookings_count / $c->capacity) >= 0.8
            );

        // ⏰ PEAK TIME
        $peakTime = Booking::select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('day','hour')
            ->orderByDesc('total')
            ->first();

        // 📊 CLASS PERFORMANCE
        $classPerformance = FitnessClass::withCount('bookings')
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'score' => $c->capacity > 0
                    ? round(($c->bookings_count / $c->capacity) * 100)
                    : 0
            ]);

        // 📈 GROWTH RATE
        $lastMonthRevenue = User::whereMonth('created_at', now()->subMonth()->month)
            ->sum('price_paid');

        $thisMonthRevenue = User::whereMonth('created_at', now()->month)
            ->sum('price_paid');

        $growthRate = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100)
            : 100;

        // 🧠 SMART INSIGHTS
        $insights = [];

        if($activeMembers < $expiredMembers){
            $insights[] = "⚠️ Retention is dropping";
        }

        if($totalBookings < 20){
            $insights[] = "📉 Low booking activity";
        }

        if($mostPopularClass){
            $insights[] = "🔥 {$mostPopularClass->name} is trending";
        }

        if($growthRate > 0){
            $insights[] = "📈 Revenue growing {$growthRate}%";
        } else {
            $insights[] = "📉 Revenue declined {$growthRate}%";
        }

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

            // 🔥 NEW FEATURES
            'lowBookingClasses',
            'almostFullClasses',
            'peakTime',
            'classPerformance',
            'growthRate',
            'insights'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->q;

        if (!$query) {
            return response()->json([
                'users' => [],
                'classes' => [],
                'bookings' => []
            ]);
        }

        return response()->json([

            'users' => User::where('name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->limit(5)
                ->get(['id','name','email','membership_type','end_date']),

            'classes' => FitnessClass::where('name', 'like', "%$query%")
                ->limit(5)
                ->get(['id','name','class_time','capacity','is_cancelled']),

            'bookings' => Booking::with([
                    'user:id,name',
                    'fitnessClass:id,name,class_time'
                ])
                ->where(function ($q) use ($query) {
                    $q->whereHas('user', function ($q) use ($query) {
                        $q->where('name', 'like', "%$query%");
                    })
                    ->orWhereHas('fitnessClass', function ($q) use ($query) {
                        $q->where('name', 'like', "%$query%");
                    });
                })
                ->limit(5)
                ->get()
        ]);
    }

    public function create()
    {
        return view('admin.create-class');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'class_time' => 'required|date',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string'
        ]);

        FitnessClass::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success','Class created successfully');
    }

    public function editClass($id)
    {
        $class = FitnessClass::findOrFail($id);
        return view('admin.edit-class', compact('class'));
    }

    public function updateClass(Request $request, $id)
    {
        $class = FitnessClass::findOrFail($id);

        $request->validate([
            'admin_notes' => 'nullable|string'
        ]);

        $class->update([
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success','Class notes updated');
    }

    public function deleteClass($id)
    {
        FitnessClass::findOrFail($id)->delete();

        return redirect()->route('admin.dashboard')
            ->with('success','Class deleted successfully');
    }

    public function cancelClass($id)
    {
        FitnessClass::findOrFail($id)->update([
            'is_cancelled' => true
        ]);

        return back()->with('success','Class cancelled');
    }

    public function toggleAttendance($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'attended' => !$booking->attended
        ]);

        return back();
    }

    public function removeBooking($id)
    {
        Booking::findOrFail($id)->delete();

        return back()->with('success','Booking removed');
    }

    public function checkinPage()
    {
        return view('admin.checkin');
    }

    public function exportRevenue()
    {
        $users = User::select(
            'name',
            'email',
            'membership_type',
            'price_paid',
            'created_at'
        )->get();

        $response = new StreamedResponse(function () use ($users) {

            $handle = fopen('php://output','w');

            fputcsv($handle,[
                'Name',
                'Email',
                'Membership Type',
                'Amount Paid (€)',
                'Date'
            ]);

            foreach($users as $user){
                fputcsv($handle,[
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