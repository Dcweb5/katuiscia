<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use Illuminate\Http\Request;

class RewardsController extends Controller
{
    public function index()
    {
        $settings = [
            'loyalty_enabled' => Setting::get('loyalty_enabled', true),
            'loyalty_points_per_euro' => Setting::get('loyalty_points_per_euro', 1),
            'loyalty_points_per_review' => Setting::get('loyalty_points_per_review', 50),
            'loyalty_points_for_signup' => Setting::get('loyalty_points_for_signup', 100),
        ];

        $rules = Setting::get('loyalty_rewards', []);

        // Load exchange history
        $exchanges = LoyaltyTransaction::with(['user', 'coupon'])
            ->where('type', 'exchange')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Optimize queries for orders using the coupons
        $couponCodes = $exchanges->pluck('coupon.code')->filter()->toArray();
        $orders = Order::whereIn('coupon_code', $couponCodes)->get()->keyBy('coupon_code');

        return view('admin.rewards.index', compact('settings', 'rules', 'exchanges', 'orders'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'loyalty_enabled' => 'required|boolean',
            'loyalty_points_per_euro' => 'required|integer|min:0',
            'loyalty_points_per_review' => 'required|integer|min:0',
            'loyalty_points_for_signup' => 'required|integer|min:0',
        ]);

        Setting::set('loyalty_enabled', (bool)$validated['loyalty_enabled']);
        Setting::set('loyalty_points_per_euro', (int)$validated['loyalty_points_per_euro']);
        Setting::set('loyalty_points_per_review', (int)$validated['loyalty_points_per_review']);
        Setting::set('loyalty_points_for_signup', (int)$validated['loyalty_points_for_signup']);

        return redirect()->back()->with('success', 'Paramètres mis à jour avec succès.');
    }

    public function addRule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'type' => 'required|in:fixed,percent,free_shipping',
            'value' => 'required|numeric|min:0',
        ]);

        $rules = Setting::get('loyalty_rewards', []);

        // Generate next ID
        $nextId = 1;
        if (count($rules) > 0) {
            $ids = array_map(fn($r) => $r['id'] ?? 0, $rules);
            $nextId = max($ids) + 1;
        }

        $newRule = [
            'id' => $nextId,
            'points' => (int)$validated['points'],
            'type' => $validated['type'],
            'value' => (float)$validated['value'],
            'name' => $validated['name'],
        ];

        $rules[] = $newRule;
        Setting::set('loyalty_rewards', $rules);

        return redirect()->back()->with('success', 'Règle de récompense ajoutée avec succès.');
    }

    public function deleteRule($id)
    {
        $rules = Setting::get('loyalty_rewards', []);
        
        $updatedRules = array_values(array_filter($rules, function($rule) use ($id) {
            return ($rule['id'] ?? null) != $id;
        }));

        Setting::set('loyalty_rewards', $updatedRules);

        return redirect()->back()->with('success', 'Règle de récompense supprimée avec succès.');
    }
}
