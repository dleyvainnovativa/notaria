<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe;

class PaymentController extends Controller
{
    public function createGuestCheckoutSession(Request $request)
    {
        $validated = $request->validate([
            'deceased_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255'],
        ]);

        try {
            $product = Product::firstOrFail();

            Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));

            $session = Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',

                'line_items' => [[
                    'price_data' => [
                        'currency' => $product->currency,
                        'product_data' => [
                            'name' => 'Memorial Digital para ' . $validated['deceased_name'],
                        ],
                        'unit_amount' => (int) ($product->price * 100),
                    ],
                    'quantity' => 1,
                ]],

                'customer_email' => $validated['user_email'],

                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),

                'metadata' => [
                    'product_id' => $product->id,
                    'deceased_name' => $validated['deceased_name'],
                    'user_name' => $validated['user_name'],
                    'user_email' => $validated['user_email'],
                ],
            ]);

            return response()->json(['url' => $session->url]);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'No se pudo crear la sesión de pago',
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        return view('pages/success');
    }

    public function paymentCancel()
    {
        return view('pages/cancel');
    }
}
