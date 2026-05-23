<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index()
    {
        return view('pages.suivi-commande');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|max:50',
            'email' => 'required|email|max:255',
        ]);

        $order = Order::with(['items', 'invoice'])
            ->where('order_number', $request->order_number)
            ->where('email', $request->email)
            ->first();

        if (!$order) {
            return back()->withErrors([
                'email' => 'Aucune commande trouvée avec ces informations. Vérifiez votre email et numéro de commande.',
            ])->withInput();
        }

        $statusLabels = [
            'pending' => ['label' => 'En attente', 'color' => '#f57f17', 'bg' => '#fff8e1'],
            'confirmed' => ['label' => 'Confirmée', 'color' => '#1565c0', 'bg' => '#e3f2fd'],
            'preparing' => ['label' => 'En préparation', 'color' => '#e65100', 'bg' => '#fff3e0'],
            'shipped' => ['label' => 'Expédiée', 'color' => '#6a1b9a', 'bg' => '#f3e5f5'],
            'delivered' => ['label' => 'Livrée', 'color' => '#2e7d32', 'bg' => '#e8f5e9'],
            'cancelled' => ['label' => 'Annulée', 'color' => '#c62828', 'bg' => '#ffebee'],
        ];

        $steps = ['confirmed', 'preparing', 'shipped', 'delivered'];
        $currentStepIndex = array_search($order->status, $steps);

        return view('pages.suivi-commande', compact('order', 'statusLabels', 'steps', 'currentStepIndex'));
    }
}
