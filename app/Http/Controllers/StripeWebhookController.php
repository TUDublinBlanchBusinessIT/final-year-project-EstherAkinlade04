<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Stripe;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            $registrationData = json_decode($session->metadata->registration_data, true);

            if ($registrationData) {

                User::create([
                    'name'            => $registrationData['name'],
                    'email'           => $registrationData['email'],
                    'password'        => Hash::make($registrationData['password']),
                    'role'            => 'member',
                    'membership_type' => $registrationData['membership_type'],
                    'gym_location'    => $registrationData['gym_location'],
                    'start_date'      => now(),
                    'end_date'        => now()->addMonth(),
                    'price_paid'      => $registrationData['price_paid'],
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}