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

        $classRevenue = Booking::where('payment_status','paid')
            ->join('fitness_classes','bookings.fitness_class_id','=','fitness_classes.id')
            ->sum('fitness_classes.price');

        $membershipRevenue = User::sum('price_paid');

        $totalRevenue = $classRevenue + $membershipRevenue;

        $monthlyRevenue = User::select(
                DB::raw('MONTH(created_at) as month_number'),
                DB::raw('MONTHNAME(created_at) as month'),
                DB::raw('SUM(price_paid) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month_number','month')
            ->orderBy('month_number')
            ->get();

        $membershipBreakdown = User::select(
                'membership_type',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('membership_type')
            ->get();

        $activeMembers = User::where('end_date','>=',now())->count();
        $expiredMembers = User::where('end_date','<',now())->count();
        $expiringSoon = User::whereBetween('end_date',[now(), now()->addDays(7)])->count();

        $bookingChart = FitnessClass::withCount('bookings')->get();
        $bookingLabels = $bookingChart->pluck('name');
        $bookingCounts = $bookingChart->pluck('bookings_count');

        $mostPopularClass = FitnessClass::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | 🔥 TOP MEMBERS LEADERBOARD
        |--------------------------------------------------------------------------
        */

        $topMembers = User::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
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
            'expiringSoon',
            'bookingLabels',
            'bookingCounts',
            'mostPopularClass',
            'topMembers'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | 🔍 ADMIN SEARCH (IMPROVED)
    |--------------------------------------------------------------------------
    */

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

        // 👤 USERS
        'users' => User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->limit(5)
            ->get(['id','name','email','membership_type','end_date']),

        // 🏋️ CLASSES
        'classes' => FitnessClass::where('name', 'like', "%$query%")
            ->limit(5)
            ->get(['id','name','class_time','capacity','is_cancelled']),

        // 📅 BOOKINGS (🔥 FINAL FIX — NO RELATION ISSUES)
        'bookings' => Booking::join('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('fitness_classes', 'bookings.fitness_class_id', '=', 'fitness_classes.id')
            ->where(function ($q) use ($query) {
                $q->where('users.name', 'like', "%$query%")
                  ->orWhere('fitness_classes.name', 'like', "%$query%");
            })
            ->limit(5)
            ->get([
                'bookings.id',
                'users.name as user_name',
                'fitness_classes.name as class_name'
            ])
    ]);
}


    /*
    |--------------------------------------------------------------------------
    | CREATE CLASS PAGE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.create-class');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE CLASS
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | EDIT CLASS NOTES
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | DELETE CLASS
    |--------------------------------------------------------------------------
    */

    public function deleteClass($id)
    {
        $class = FitnessClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.dashboard')
            ->with('success','Class deleted successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL CLASS
    |--------------------------------------------------------------------------
    */

    public function cancelClass($id)
    {
        $class = FitnessClass::findOrFail($id);

        $class->update([
            'is_cancelled' => true
        ]);

        return back()->with('success','Class cancelled');
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function toggleAttendance($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'attended' => !$booking->attended
        ]);

        return back();
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE BOOKING
    |--------------------------------------------------------------------------
    */

    public function removeBooking($id)
    {
        Booking::findOrFail($id)->delete();

        return back()->with('success','Booking removed');
    }


    /*
    |--------------------------------------------------------------------------
    | QR SCANNER PAGE
    |--------------------------------------------------------------------------
    */

    public function checkinPage()
    {
        return view('admin.checkin');
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT REVENUE CSV
    |--------------------------------------------------------------------------
    */

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