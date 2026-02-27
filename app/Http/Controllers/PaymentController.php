<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout()
    {
        // 🔥 Force load directly from .env (avoids config cache issue)
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = Auth::user();

        $amount = 4100; // €41 in cents

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Vault Fitness Membership',
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

        // Activate membership for 1 month
        $user->update([
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'price_paid' => 41.00
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Membership activated successfully!');
    }

    public function cancel()
    {
        return redirect()->route('dashboard')
            ->with('error', 'Payment cancelled.');
    }
}