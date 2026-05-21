<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function getCart(): ?Cart
    {
        if (auth()->check()) {
            return Cart::with('items.product')->where('user_id', auth()->id())->first();
        }
        return Cart::with('items.product')->where('session_id', session()->getId())->whereNull('user_id')->first();
    }

    public function index()
    {
        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect('/panier')->with('error', 'Votre panier est vide.');
        }

        return view('pages.paiement', compact('cart'));
    }

    public function store(Request $request)
    {
        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect('/panier')->with('error', 'Votre panier est vide.');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'address2' => 'nullable|string|max:500',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:card,cod',
            'coupon_code' => 'nullable|string|max:50',
            'coupon_discount' => 'nullable|numeric|min:0',
        ]);

        $discount = 0;
        $coupon = null;
        $couponCode = $validated['coupon_code'] ?? null;

        // Fallback : si le formulaire n'a pas transmis le code, lire depuis la session
        if (!$couponCode || strlen($couponCode) === 0) {
            $couponCode = session('coupon.code');
        }

        if ($couponCode && strlen($couponCode) > 0) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid($cart->total)) {
                $discount = $coupon->calculateDiscount($cart->total);
                $coupon->increment('used_count');
                $coupon->update(['last_used_at' => now()]);
            }
        }

        $total = max(0, $cart->total - $discount);

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'KAT-' . strtoupper(substr(uniqid(), -6)),
            'email' => $validated['email'],
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'address' => $validated['address'],
            'address2' => $validated['address2'] ?? null,
            'postal_code' => $validated['postal_code'],
            'city' => $validated['city'],
            'country' => $validated['country'] ?? 'FR',
            'phone' => $validated['phone'] ?? null,
            'subtotal' => $cart->total,
            'discount' => $discount,
            'shipping' => 0,
            'total' => $total,
            'coupon_code' => $couponCode ?: null,
            'status' => 'confirmed',
            'payment_method' => $validated['payment_method'],
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? 'Produit',
                'price' => $item->price,
                'quantity' => $item->quantity,
            ]);
        }

        // Vider le panier + coupon après commande
        $cart->items()->delete();
        $cart->delete();
        session()->forget('coupon');

        // Points de fidélité
        if (auth()->check()) {
            auth()->user()->increment('loyalty_points', (int) $total);
        }

        return redirect('/checkout/success/' . $order->order_number);
    }

    public function success(string $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pages.checkout-success', compact('order'));
    }
}
