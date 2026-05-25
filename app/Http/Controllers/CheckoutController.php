<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\StripeClient;

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
        $couponCode = $validated['coupon_code'] ?? session('coupon.code') ?? null;

        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid($cart->total)) {
                $discount = $coupon->calculateDiscount($cart->total);
            }
        }

        $total = max(0, $cart->total - $discount);

        // Si le panier contient des items de collection, appliquer la réduction
        $collectionItem = $cart->items->where('collection_id', '!=', null)->first();
        if ($collectionItem && !$couponCode) {
            $collection = \App\Models\Collection::find($collectionItem->collection_id);
            if ($collection) {
                $discount = $cart->total - $collection->price;
                $total = $collection->price;
            }
        }

        if ($validated['payment_method'] === 'cod') {
            // Paiement à la livraison : flow classique
            $order = $this->createOrder($validated, $cart, $discount, $couponCode, $total);
            $order->update(['payment_status' => 'paid', 'paid_at' => now()]);
            $this->clearCart($cart);
            if (auth()->check()) auth()->user()->increment('loyalty_points', (int) $total);
            try { $this->generateInvoice($order); } catch (\Exception $e) { \Log::error('COD invoice failed: ' . $e->getMessage()); }
            return redirect('/checkout/success/' . $order->order_number);
        }

        // Paiement par carte : Stripe Checkout
        $order = $this->createOrder($validated, $cart, $discount, $couponCode, $total);
        $order->update(['payment_status' => 'pending_payment']);

        // Lier au compte utilisateur si l'email correspond
        if (!auth()->check()) {
            $user = \App\Models\User::where('email', $validated['email'])->first();
            if ($user) {
                $order->update(['user_id' => $user->id]);
            }
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($cart->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $item->product->name ?? 'Produit'],
                    'unit_amount' => (int) round($item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Appliquer la réduction si coupon
        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => url('/checkout/success/' . $order->order_number . '?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/paiement'),
            'customer_email' => $validated['email'],
            'metadata' => ['order_id' => $order->id],
        ];

        if ($discount > 0 && $couponCode) {
            $stripeCoupon = $stripe->coupons->create([
                'percent_off' => round(($discount / $cart->total) * 100),
                'duration' => 'once',
            ]);
            $sessionData['discounts'] = [['coupon' => $stripeCoupon->id]];
        }

        $session = $stripe->checkout->sessions->create($sessionData);
        $order->update(['stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    private function createOrder($data, $cart, $discount, $couponCode, $total): Order
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'KAT-' . strtoupper(substr(uniqid(), -6)),
            'email' => $data['email'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'address' => $data['address'],
            'address2' => $data['address2'] ?? null,
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
            'country' => $data['country'] ?? 'FR',
            'phone' => $data['phone'] ?? null,
            'subtotal' => $cart->total,
            'discount' => $discount,
            'shipping' => 0,
            'total' => $total,
            'coupon_code' => $couponCode ?: null,
            'status' => 'pending_payment',
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'payment_gateway' => $data['payment_method'] === 'cod' ? 'cod' : 'stripe',
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? 'Produit',
                'price' => $item->price,
                'quantity' => $item->quantity,
            ]);
        }

        // Tracker l'achat dans le lead si existe
        $lead = \App\Models\Lead::where('email', $data['email'])->first();
        if ($lead) {
            $lead->update([
                'purchased' => true,
                'total_revenue' => ($lead->total_revenue ?? 0) + $total,
                'orders_count' => ($lead->orders_count ?? 0) + 1,
            ]);
        }

        return $order;
    }

    private function clearCart($cart): void
    {
        $cart->items()->delete();
        $cart->delete();
        session()->forget('coupon');
    }

    public function success(string $orderNumber)
    {
        $order = Order::with(['items', 'invoice'])->where('order_number', $orderNumber)->firstOrFail();

        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        // Si paiement Stripe, vérifier le statut
        $sessionId = request()->query('session_id');
        if ($sessionId && $order->stripe_session_id) {
            try {
                $stripe = new StripeClient(config('services.stripe.secret'));
                $session = $stripe->checkout->sessions->retrieve($sessionId);
                if ($session->payment_status === 'paid' && $order->payment_status !== 'paid') {
                    $this->confirmOrder($order);
                }
            } catch (\Exception $e) {
                // Silently continue
            }
        }

        return view('pages.checkout-success', compact('order'));
    }

    public function confirmOrder(Order $order): void
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'paid_at' => now(),
        ]);

        if (auth()->check()) {
            auth()->user()->increment('loyalty_points', (int) $order->total);
        }

        // Générer la facture
        try { $this->generateInvoice($order); } catch (\Exception $e) { \Log::error('Invoice failed: ' . $e->getMessage()); }

        // Vider le panier après confirmation
        $cart = \App\Models\Cart::where('user_id', $order->user_id)->first()
            ?? \App\Models\Cart::where('session_id', session()->getId())->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }
        session()->forget('coupon');
    }

    private function generateInvoice(Order $order): void
    {
        // Ne pas générer de doublon
        if ($order->invoice) return;

        $count = \App\Models\Invoice::count();
        $invoiceNumber = 'FACT-' . now()->format('Y') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $invoice = \App\Models\Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => $invoiceNumber,
            'file_path' => 'invoices/' . $invoiceNumber . '.pdf',
            'total' => $order->total,
            'is_emailed' => false,
        ]);

        $order->load('items');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.template', compact('order', 'invoice'));
        $pdfPath = storage_path('app/invoices/' . $invoiceNumber . '.pdf');

        if (!is_dir(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }
        $pdf->save($pdfPath);

        \Mail::to($order->email, $order->firstname . ' ' . $order->lastname)
            ->send(new \App\Mail\InvoiceMail($order, $invoice, $pdfPath));

        $invoice->update(['is_emailed' => true]);
    }
}

