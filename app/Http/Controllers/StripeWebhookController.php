<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            if ($secret) {
                $event = Webhook::constructEvent($payload, $sigHeader, $secret);
            } else {
                $event = json_decode($payload);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $order = Order::where('stripe_session_id', $session->id)->first();

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'paid_at' => now(),
                ]);

                // Points fidélité
                if ($order->user_id) {
                    $order->user->increment('loyalty_points', (int) $order->total);
                }

                // Vider le panier
                if ($order->user_id) {
                    $cart = \App\Models\Cart::where('user_id', $order->user_id)->first();
                } else {
                    $cart = \App\Models\Cart::where('email', $order->email)->first();
                }
                if ($cart) {
                    $cart->items()->delete();
                    $cart->delete();
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
