<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTRATION CHECKOUT (PAY BEFORE ACCOUNT CREATION)
    |--------------------------------------------------------------------------
    */

    public function registerCheckout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email',
            'password'        => 'required|min:6|confirmed',
            'membership_type' => 'required|string',
            'gym_location'    => 'required|string',
        ]);

        session(['registration_data' => $validated]);

        $amount = $this->getPlanPrice($validated['membership_type']);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => ucfirst($validated['membership_type']) . ' Membership',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'success_url' => url('/register/success'),
            'cancel_url' => url('/register'),
        ]);

        return redirect($session->url);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER AFTER STRIPE SUCCESS
    |--------------------------------------------------------------------------
    */

    public function registerSuccess()
    {
        $data = session('registration_data');

        if (!$data) {
            return redirect()->route('register');
        }

        $price = $this->getPlanPrice($data['membership_type']);

        $user = User::create([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'role'            => 'member',
            'membership_type' => $data['membership_type'],
            'gym_location'    => $data['gym_location'],
            'start_date'      => now(),
            'end_date'        => now()->addMonth(),
            'price_paid'      => $price / 100,
        ]);

        Auth::login($user);

        session()->forget('registration_data');

        return redirect()->route('dashboard')
            ->with('success', 'Welcome to Vault Fitness! 🎉 Membership activated.');
    }

    /*
    |--------------------------------------------------------------------------
    | EXISTING USER RENEWAL CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function checkout()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = Auth::user();

        $amount = $this->getPlanPrice($user->membership_type);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Membership Renewal',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'success_url' => url('/payment-success'),
            'cancel_url' => url('/payment-cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        $user = Auth::user();

        $user->update([
            'start_date' => now(),
            'end_date'   => now()->addMonth(),
            'price_paid' => $this->getPlanPrice($user->membership_type) / 100
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Membership renewed successfully!');
    }

    public function cancel()
    {
        return redirect()->route('dashboard')
            ->with('error', 'Payment cancelled.');
    }

    /*
    |--------------------------------------------------------------------------
    | PLAN PRICING
    |--------------------------------------------------------------------------
    */

    private function getPlanPrice($plan)
    {
        return match($plan) {
            'payg_1day' => 1400,
            'roaming_monthly' => 4100,
            'membership_monthly' => 3800,
            default => 4100,
        };
    }
}