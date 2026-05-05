<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\QRController;
use App\Models\Memorial;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Charge;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentCompletedMail;
use App\Mail\WelcomeCompletedMail;
use Stripe\Checkout\Session as StripeSession;


class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Stripe::setApiKey(env("STRIPE_SECRET"));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = env("STRIPE_WEBHOOK_SECRET");

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (\Exception $e) {
            return response('Invalid webhook', 400);
        }

        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            if (Payment::where('stripe_session_id', $session->id)->exists()) {
                return response('Already processed', 200);
            }

            // 🔥 Get full session with expanded data
            $fullSession = StripeSession::retrieve([
                'id' => $session->id,
                'expand' => ['payment_intent', 'customer_details']
            ]);

            $paymentIntent = $fullSession->payment_intent;
            $charge = null;

            if ($paymentIntent && $paymentIntent->latest_charge) {
                $charge = Charge::retrieve($paymentIntent->latest_charge);
            }

            $user = User::firstOrCreate(
                ['email' => $session->metadata->user_email],
                [
                    'name' => $session->metadata->user_name,
                    'firebase_uid' => null,
                ]
            );

            if ($user->wasRecentlyCreated) {
                Mail::to($user->email)->send(
                    new WelcomeCompletedMail($user, null)
                );
            }

            $memorial = Memorial::create([
                'user_id'       => $user->id,
                'slug'          => Str::slug($session->metadata->deceased_name) . '-' . uniqid(),
                'deceased_name' => $session->metadata->deceased_name,
                'status'        => 'active',
            ]);

            $qrCode = QRController::create($memorial->id);

            $payment = Payment::create([
                'user_id' => $user->id,
                'memorial_id' => $memorial->id,

                // Stripe IDs
                'stripe_payment_intent_id' => $session->payment_intent,
                'stripe_session_id' => $session->id,
                'stripe_customer_id' => $fullSession->customer,
                'stripe_charge_id' => $charge->id ?? null,

                // Amounts
                'amount' => $session->amount_total / 100,
                'amount_total' => $session->amount_total / 100,
                'amount_subtotal' => $session->amount_subtotal / 100,
                'tax' => ($session->total_details->amount_tax ?? 0) / 100,
                'currency' => $session->currency,
                'currency_symbol' => '$',

                // Status
                'status' => 'paid',
                'paid_at' => now(),

                // Customer snapshot
                'customer_email' => $fullSession->customer_details->email ?? null,
                'customer_name' => $fullSession->customer_details->name ?? null,

                // Payment method
                'payment_method' => $charge->payment_method_details->type ?? null,
                'card_brand' => $charge->payment_method_details->card->brand ?? null,
                'card_last4' => $charge->payment_method_details->card->last4 ?? null,

                // Receipts / invoices
                'receipt_url' => $charge->receipt_url ?? null,
                'invoice_url' => $fullSession->invoice ?? null,
                // Product context
                'product_name' => 'Memorial'
            ]);

            Mail::to($user->email)->send(
                new PaymentCompletedMail($user, $memorial, $payment, $qrCode)
                // new PaymentCompletedMail($user, $memorial)
            );
        }

        return response('OK', 200);
    }
}
