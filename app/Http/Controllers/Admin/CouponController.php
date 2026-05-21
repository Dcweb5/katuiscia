<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::orderBy('created_at', 'desc');

        if ($search = $request->query('search')) {
            $query->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->query('type')) {
            $query->where('type', $request->query('type'));
        }

        if ($request->query('active') === '1') {
            $query->where('is_active', true);
        } elseif ($request->query('active') === '0') {
            $query->where('is_active', false);
        }

        $coupons = $query->paginate(20)->appends($request->query());

        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)->count(),
            'used' => Coupon::sum('used_count'),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validated['type'] === 'free_shipping') {
            $validated['value'] = null;
        }

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon créé.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validated['type'] === 'free_shipping') {
            $validated['value'] = null;
        }

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon mis à jour.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon supprimé.');
    }

    public function validate(Request $request)
    {
        $request->validate(['code' => 'required|string', 'cart_total' => 'required|numeric']);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Code promo invalide.']);
        }

        if (!$coupon->isValid((float) $request->cart_total)) {
            $msg = 'Ce code promo n\'est plus valide.';
            if ($coupon->expires_at && now()->gt($coupon->expires_at)) $msg = 'Ce code promo a expiré.';
            elseif ($coupon->min_order_amount && $request->cart_total < $coupon->min_order_amount)
                $msg = 'Minimum ' . number_format($coupon->min_order_amount, 0, ',', ' ') . ' € d\'achat requis.';
            elseif ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses)
                $msg = 'Ce code promo a atteint sa limite d\'utilisation.';
            return response()->json(['valid' => false, 'message' => $msg]);
        }

        $discount = $coupon->calculateDiscount((float) $request->cart_total);

        return response()->json([
            'valid' => true,
            'coupon_id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'discount' => round($discount, 2),
            'label' => $coupon->getDiscountLabel(),
            'message' => 'Code promo appliqué ! ' . $coupon->getDiscountLabel(),
        ]);
    }
}
