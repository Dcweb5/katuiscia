<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::orderBy('id')->get();
        return view('admin.shipping-zones.index', compact('zones'));
    }

    public function update(Request $request, ShippingZone $zone)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'delivery_time' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $zone->update($validated);

        return redirect()->route('admin.shipping-zones.index')->with('success', 'Zone de livraison mise à jour avec succès.');
    }

    public function toggle(ShippingZone $zone)
    {
        $zone->update([
            'is_active' => !$zone->is_active
        ]);

        return redirect()->route('admin.shipping-zones.index')->with('success', 'Statut de la zone de livraison mis à jour.');
    }
}
