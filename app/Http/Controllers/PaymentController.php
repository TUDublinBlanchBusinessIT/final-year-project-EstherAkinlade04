<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MembershipPlan;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function checkout()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = Auth::user();

        if (!$user->membership_plan_id) {
            return redirect()->route('dashboard')
                ->with('error', 'No membership plan selected.');
        }

        $plan = MembershipPlan::findOrFail($user->membership_plan_id);

        $session = Session::create([
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
            'success_url' => url('/payment-success'),
            'cancel_url' => url('/payment-cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        $user = Auth::user();

        if (!$user->membership_plan_id) {
            return redirect()->route('dashboard');
        }

        $plan = MembershipPlan::findOrFail($user->membership_plan_id);

        $user->update([
            'start_date' => now(),
            'end_date' => now()->addDays($plan->duration_days),
            'price_paid' => $plan->price,
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