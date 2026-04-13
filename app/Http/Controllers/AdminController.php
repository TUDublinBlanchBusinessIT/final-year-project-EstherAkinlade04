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
        // ✅ CURRENT GYM
        $gymId = auth()->user()->gym_id;

        // 📅 Classes (FILTERED)
        $classes = FitnessClass::withCount('bookings')
            ->with(['bookings.user'])
            ->when($gymId, fn($q) => $q->where('gym_id', $gymId))
            ->orderBy('class_time')
            ->paginate(5);

        // 📊 Basic stats
        $totalUsers = User::where('gym_id', $gymId)->count();

        $totalBookings = Booking::whereHas('fitnessClass', function ($q) use ($gymId) {
            if ($gymId) $q->where('gym_id', $gymId);
        })->count();

        $totalClasses = FitnessClass::when($gymId, fn($q) => $q->where('gym_id', $gymId))->count();

        // 💰 Revenue (FILTERED)
        $classRevenue = Booking::where('payment_status','paid')
            ->whereHas('fitnessClass', function ($q) use ($gymId) {
                if ($gymId) $q->where('gym_id', $gymId);
            })
            ->join('fitness_classes','bookings.fitness_class_id','=','fitness_classes.id')
            ->sum('fitness_classes.price');

        $membershipRevenue = User::where('gym_id', $gymId)->sum('price_paid');
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

        // ❌ Cancelled classes (FILTERED)
        $cancelledClasses = FitnessClass::where('is_cancelled', true)
            ->when($gymId, fn($q) => $q->where('gym_id', $gymId))
            ->orderBy('class_time')
            ->get(['name','class_time']);

        // 💰 Revenue per class (FILTERED)
        $classRevenueData = FitnessClass::withCount('bookings')
            ->when($gymId, fn($q) => $q->where('gym_id', $gymId))
            ->get()
            ->map(fn($class) => [
                'name' => $class->name,
                'revenue' => $class->bookings_count * $class->price
            ]);

        // 🔥 HEATMAP (FILTERED)
        $activityData = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereHas('fitnessClass', function ($q) use ($gymId) {
                if ($gymId) $q->where('gym_id', $gymId);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($d) => [
                'date' => $d->date,
                'total' => (int)$d->total
            ])
            ->values();

        // 📊 Booking chart
        $bookingChart = FitnessClass::withCount('bookings')
            ->when($gymId, fn($q) => $q->where('gym_id', $gymId))
            ->get();

        $bookingLabels = $bookingChart->pluck('name');
        $bookingCounts = $bookingChart->pluck('bookings_count');

        // 🔥 Most popular class (FILTERED)
        $mostPopularClass = FitnessClass::withCount('bookings')
            ->when($gymId, fn($q) => $q->where('gym_id', $gymId))
            ->orderByDesc('bookings_count')
            ->first();

        // 🏆 Top members (GLOBAL)
        $topMembers = User::withCount('bookings')
            ->where('gym_id', $gymId)
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        // 🚨 LOW BOOKING ALERT (FILTERED)
        $lowBookingClasses = FitnessClass::withCount('bookings')
            ->when($gymId, fn($q) => $q->where('gym_id', $gymId))
            ->having('bookings_count','<',3)
            ->get();

        // 🔥 ALMOST FULL (FILTERED)
        $almostFullClasses = FitnessClass::withCount('bookings')
            ->when($gymId, fn($q) => $q->where('gym_id', $gymId))
            ->get()
            ->filter(fn($c) =>
                $c->capacity > 0 &&
                ($c->bookings_count / $c->capacity) >= 0.8
            );

        // ⏰ PEAK TIME (FILTERED)
        $peakTime = Booking::select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->whereHas('fitnessClass', function ($q) use ($gymId) {
                if ($gymId) $q->where('gym_id', $gymId);
            })
            ->groupBy('day','hour')
            ->orderByDesc('total')
            ->first();

        // 📊 CLASS PERFORMANCE (FILTERED)
        $classPerformance = FitnessClass::withCount('bookings')
            ->when($gymId, fn($q) => $q->where('gym_id', $gymId))
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

        // ✅ USERS (GLOBAL)
        $users = User::withCount('bookings')
            ->where('gym_id', $gymId)
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

    public function store(Request $request)
    {
        $gymId = session('selected_gym_id');

        if (!$gymId) {
            return back()->with('error', 'Please select a gym first');
        }

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
            ->with('success','Class created successfully');
    }

    // 🔽 EVERYTHING ELSE UNCHANGED

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted');
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

        $gymId = session('selected_gym_id');

        return response()->json([
            'users' => User::where('gym_id', $gymId)
    ->where(function($q) use ($query){
        $q->where('name','like',"%$query%")
          ->orWhere('email','like',"%$query%");
    })
    ->limit(5)
    ->get(),

            'classes' => FitnessClass::when($gymId, fn($q)=>$q->where('gym_id',$gymId))
                ->where('name', 'like', "%$query%")
                ->limit(5)
                ->get(),

            'bookings' => Booking::with(['user','fitnessClass'])
                ->whereHas('fitnessClass', function ($q) use ($gymId) {
                    if ($gymId) $q->where('gym_id', $gymId);
                })
                ->limit(5)
                ->get()
        ]);
    }

    public function create()
    {
        return view('admin.create-class');
    }

    public function editClass($id)
    {
        $class = FitnessClass::findOrFail($id);
        return view('admin.edit-class', compact('class'));
    }

    public function updateClass(Request $request, $id)
    {
        $class = FitnessClass::findOrFail($id);

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
            'name','email','membership_type','price_paid','created_at'
        )->get();

        $response = new StreamedResponse(function () use ($users) {

            $handle = fopen('php://output','w');

            fputcsv($handle,['Name','Email','Membership Type','Amount Paid (€)','Date']);

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