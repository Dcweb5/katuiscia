<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->is_admin) return redirect('/admin');
        $user = auth()->user();
        $recentOrders = \App\Models\Order::with(['items'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->take(2)->get();
        $ordersCount = \App\Models\Order::where('user_id', $user->id)->count();
        $activeOrdersCount = \App\Models\Order::where('user_id', $user->id)->whereIn('status', ['confirmed', 'preparing', 'shipped'])->count();
        $reviewsCount = \App\Models\Review::where('user_id', $user->id)->count();
        return view('account.dashboard', compact('user', 'recentOrders', 'ordersCount', 'activeOrdersCount', 'reviewsCount'));
    }

    public function orders()
    {
        $user = auth()->user();
        $orders = \App\Models\Order::with(['items', 'invoice'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('account.orders', compact('user', 'orders'));
    }

    public function rewards()
    {
        if (auth()->user()->is_admin) return redirect('/admin');
        $user = auth()->user();
        $isEnabled = \App\Services\LoyaltyService::isEnabled();
        
        $transactions = \App\Models\LoyaltyTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $coupons = \App\Models\Coupon::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $rewardsOptions = \App\Models\Setting::get('loyalty_rewards', []);
        
        return view('account.rewards', compact('user', 'isEnabled', 'transactions', 'coupons', 'rewardsOptions'));
    }

    public function exchangeReward(Request $request)
    {
        if (!\App\Services\LoyaltyService::isEnabled()) {
            return response()->json(['error' => 'Le système de récompenses est actuellement désactivé.'], 403);
        }

        $validated = $request->validate([
            'reward_id' => 'required|integer',
        ]);

        $user = auth()->user();
        $rewardsOptions = \App\Models\Setting::get('loyalty_rewards', []);
        
        $reward = null;
        foreach ($rewardsOptions as $opt) {
            if (isset($opt['id']) && $opt['id'] == $validated['reward_id']) {
                $reward = $opt;
                break;
            }
        }

        if (!$reward) {
            return response()->json(['error' => 'Récompense introuvable.'], 404);
        }

        $pointsRequired = (int) $reward['points'];
        if ($user->loyalty_points < $pointsRequired) {
            return response()->json(['error' => 'Points insuffisants pour cette récompense.'], 400);
        }

        $couponCode = 'K-LOY-' . strtoupper(\Illuminate\Support\Str::random(8));

        return \Illuminate\Support\Facades\DB::transaction(function() use ($user, $reward, $pointsRequired, $couponCode) {
            $coupon = \App\Models\Coupon::create([
                'code' => $couponCode,
                'type' => $reward['type'] ?? 'fixed',
                'value' => (float) ($reward['value'] ?? 0),
                'min_order_amount' => 0,
                'max_uses' => 1,
                'used_count' => 0,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
                'description' => 'Coupon fidélité : ' . ($reward['name'] ?? 'Récompense'),
                'user_id' => $user->id,
            ]);

            \App\Services\LoyaltyService::deductPoints(
                $user, 
                $pointsRequired, 
                'exchange', 
                'Échange contre la récompense : ' . ($reward['name'] ?? 'Récompense'),
                $coupon->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Félicitations ! Votre récompense a été échangée.',
                'coupon' => [
                    'code' => $coupon->code,
                    'description' => $coupon->description,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'expires_at' => $coupon->expires_at->format('d/m/Y'),
                ]
            ]);
        });
    }

    public function reviews()
    {
        if (auth()->user()->is_admin) return redirect('/admin');
        $user = auth()->user();
        
        // Avis déjà écrits
        $reviews = \App\Models\Review::with('product.images')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Produits achetés mais pas encore évalués
        $reviewedProductIds = $reviews->pluck('product_id');
        $orderedProductIds = \App\Models\Order::where('user_id', $user->id)
            ->whereIn('status', ['delivered','shipped'])
            ->with('items')
            ->get()
            ->pluck('items.*.product_id')
            ->flatten()
            ->unique()
            ->filter()
            ->diff($reviewedProductIds);
            
        $pendingProducts = $orderedProductIds->count() > 0 
            ? \App\Modules\Product\Models\Product::with('images')->whereIn('id', $orderedProductIds)->get()
            : collect();
        
        $approvedCount = $reviews->where('is_approved', true)->count();
        $pendingCount = $reviews->where('is_approved', false)->count();
        
        return view('account.reviews', compact('user', 'reviews', 'pendingProducts', 'approvedCount', 'pendingCount'));
    }

    public function returns()
    {
        if (auth()->user()->is_admin) return redirect('/admin');
        $user = auth()->user();
        return view('account.returns', compact('user'));
    }
}
