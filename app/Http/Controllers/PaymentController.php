<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Str;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function checkout()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $user = auth()->user();

        $amount = 4100; // €41 example

        $session = Session::create([
            'payment_method_types' => ['card'],
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
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
        ]);

        Payment::create([
            'user_id' => $user->id,
            'stripe_session_id' => $session->id,
            'reference' => strtoupper(Str::random(10)),
            'amount' => $amount / 100,
            'status' => 'pending'
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($request->session_id);

        if ($session->payment_status === 'paid') {

            $user = auth()->user();

            Payment::where('stripe_session_id', $session->id)
                ->update(['status' => 'paid']);

            $user->start_date = now();
            $user->end_date = now()->addMonth();
            $user->price_paid = 41.00;
            $user->plan_duration = 'monthly';
            $user->save();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Membership activated!');
    }

    public function cancel()
    {
        return redirect()->route('dashboard')
            ->with('error', 'Payment cancelled.');
    }
}