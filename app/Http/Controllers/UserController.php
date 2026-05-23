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
        $recentOrders = collect([]);
        return view('account.dashboard', compact('user', 'recentOrders'));
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
        return view('account.rewards', compact('user'));
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
