<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MembershipPlan;
use App\Mail\WelcomeMemberMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Register Page
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        $plans = MembershipPlan::where('is_active', 1)->get();
        return view('register', compact('plans'));
    }

    /*
    |--------------------------------------------------------------------------
    | Registration → Stripe Checkout
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'gym_location' => 'required|string',
            'membership_plan_id' => 'required|exists:membership_plans,id',
        ]);

        $plan = MembershipPlan::findOrFail($validated['membership_plan_id']);

        // Store registration data temporarily before payment
        session([
            'registration_data' => $validated,
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripeSession = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $plan->name,
                    ],
                    'unit_amount' => $plan->price * 100,
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('register.success'),
            'cancel_url' => route('register.cancel'),
        ]);

        return redirect($stripeSession->url);
    }

    /*
    |--------------------------------------------------------------------------
    | Stripe Success → Create User + Send Welcome Email
    |--------------------------------------------------------------------------
    */

    public function registrationSuccess()
    {
        $data = session('registration_data');

        // 🚫 Safety check
        if (!$data) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Registration session expired. Please try again.']);
        }

        $plan = MembershipPlan::findOrFail($data['membership_plan_id']);

        // ✅ Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'member',
            'gym_location' => $data['gym_location'],
            'membership_plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($plan->duration_days),
            'price_paid' => $plan->price,
        ]);

        // 📧 Send welcome email (safe for demo)
        try {
            Mail::to($user->email)
                ->send(new WelcomeMemberMail($user));
        } catch (\Exception $e) {
            // Fail silently (important for smooth UX)
        }

        // 🧹 Clear temporary session data
        session()->forget('registration_data');

        // 🔐 Auto login
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Welcome to Vault Fitness! 📩 Check your email.');
    }

    /*
    |--------------------------------------------------------------------------
    | Stripe Cancel
    |--------------------------------------------------------------------------
    */

    public function registrationCancel()
    {
        session()->forget('registration_data');

        return redirect()->route('register')
            ->withErrors(['error' => 'Payment cancelled.']);
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}