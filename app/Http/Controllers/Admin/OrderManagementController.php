<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->orderBy('created_at', 'desc');

        // Filtre par statut
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Recherche
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par période
        if ($from = $request->query('date_from')) {
            $query->where('created_at', '>=', $from . ' 00:00:00');
        }
        if ($to = $request->query('date_to')) {
            $query->where('created_at', '<=', $to . ' 23:59:59');
        }

        // Tri
        if ($request->query('sort') === 'oldest') $query->reorder()->orderBy('created_at');

        $orders = $query->paginate(20)->appends($request->query());

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        $statuses = ['pending' => 'En attente', 'confirmed' => 'Confirmée', 'preparing' => 'En préparation', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'];

        return view('admin.orders.index', compact('orders', 'stats', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Statut mis à jour : ' . $request->status);
    }
}
