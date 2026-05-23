<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Récupère ou crée le panier actif.
     */
    private function getOrCreateCart(): Cart
    {
        if (auth()->check()) {
            // Utilisateur connecté : merge le panier guest si existant
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
            $guestCart = Cart::where('session_id', session()->getId())
                ->whereNull('user_id')
                ->first();

            if ($guestCart && $guestCart->id !== $cart->id) {
                foreach ($guestCart->items as $item) {
                    $existing = $cart->items()->where('product_id', $item->product_id)->first();
                    if ($existing) {
                        $existing->increment('quantity', $item->quantity);
                    } else {
                        $cart->items()->create([
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                        ]);
                    }
                }
                $guestCart->delete();
            }
            return $cart->load('items.product.categories');
        }

        // Guest
        $sessionId = session()->getId();
        $cart = Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
        return $cart->load('items.product.categories');
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        return view('pages.panier', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'collection_id' => 'nullable|exists:collections,id',
            'quantity' => 'nullable|integer|min:1|max:10',
        ]);

        $cart = $this->getOrCreateCart();
        $quantity = $request->integer('quantity', 1);

        // Collection mode: add all products with pack pricing
        if ($collectionId = $request->collection_id) {
            $collection = \App\Models\Collection::with('products')->findOrFail($collectionId);
            $products = $collection->products;
            if ($products->isEmpty()) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'Collection vide.'])
                    : back()->with('error', 'Collection vide.');
            }
            $pricePerProduct = $collection->price / $products->count();
            foreach ($products as $product) {
                $existing = $cart->items()->where('product_id', $product->id)->where('collection_id', $collectionId)->first();
                if ($existing) {
                    $existing->increment('quantity', $quantity);
                } else {
                    $cart->items()->create([
                        'product_id' => $product->id,
                        'collection_id' => $collectionId,
                        'quantity' => $quantity,
                        'price' => $pricePerProduct,
                    ]);
                }
            }
        } elseif ($productId = $request->product_id) {
            $product = \App\Modules\Product\Models\Product::findOrFail($productId);
            $existing = $cart->items()->where('product_id', $productId)->whereNull('collection_id')->first();
            if ($existing) {
                $existing->increment('quantity', $quantity);
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->final_price,
                ]);
            }
        } else {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Produit ou collection requis.'])
                : back()->with('error', 'Produit ou collection requis.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ajouté au panier',
                'cart_count' => $cart->items_count,
                'cart_total' => number_format($cart->total, 0, ',', ' ') . ' €',
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);

        $cart = $this->getOrCreateCart();
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        $item->update(['quantity' => $request->quantity]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'item_subtotal' => number_format($item->subtotal, 0, ',', ' ') . ' €',
                'cart_total' => number_format($cart->fresh()->total, 0, ',', ' ') . ' €',
            ]);
        }

        return back();
    }

    public function remove(CartItem $item)
    {
        $cart = $this->getOrCreateCart();
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        $item->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'cart_total' => number_format($cart->fresh()->total, 0, ',', ' ') . ' €',
                'cart_count' => $cart->fresh()->items->sum('quantity'),
            ]);
        }

        return back()->with('success', 'Produit retiré du panier.');
    }

    public function count()
    {
        $cart = $this->getOrCreateCart();
        return response()->json([
            'count' => $cart->items->sum('quantity'),
            'total' => number_format($cart->total, 0, ',', ' ') . ' €',
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $cart = $this->getOrCreateCart();
        $coupon = \App\Models\Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Code promo invalide.']);
        }

        if (!$coupon->isValid($cart->total)) {
            $msg = 'Code expiré ou invalide.';
            if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) $msg = 'Limite atteinte.';
            elseif ($coupon->min_order_amount && $cart->total < $coupon->min_order_amount) $msg = 'Min. ' . number_format($coupon->min_order_amount, 0) . ' € requis.';
            return response()->json(['valid' => false, 'message' => $msg]);
        }

        $discount = $coupon->calculateDiscount($cart->total);
        $newTotal = max(0, $cart->total - $discount);

        // Stocker le coupon en session (force save pour AJAX)
        session(['coupon' => ['code' => $coupon->code, 'discount' => $discount, 'type' => $coupon->type, 'label' => $coupon->getDiscountLabel()]]);
        session()->save();

        return response()->json([
            'valid' => true,
            'discount' => number_format($discount, 0, ',', ' ') . ' €',
            'discount_raw' => $discount,
            'new_total' => number_format($newTotal, 0, ',', ' ') . ' €',
            'message' => $coupon->getDiscountLabel(),
        ]);
    }
}
