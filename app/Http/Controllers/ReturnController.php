<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $returns = ReturnRequest::with(['order', 'item'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $orders = Order::with('items')->where('user_id', $user->id)->where('status', 'delivered')->orderBy('created_at', 'desc')->get();

        return view('account.returns', compact('user', 'returns', 'orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'nullable|exists:order_items,id',
            'type' => 'required|in:return,exchange',
            'reason' => 'required|string|max:2000',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        if ($order->user_id !== auth()->id()) abort(403);

        ReturnRequest::create([
            'user_id' => auth()->id(),
            'order_id' => $validated['order_id'],
            'order_item_id' => $validated['order_item_id'] ?? null,
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Demande de retour envoyée. Nous vous répondrons sous 48h.');
    }

    public function cancel(ReturnRequest $returnRequest)
    {
        if ($returnRequest->user_id !== auth()->id()) abort(403);
        if (!in_array($returnRequest->status, ['pending', 'approved'])) {
            return back()->with('error', 'Cette demande ne peut plus être annulée.');
        }
        $returnRequest->update(['status' => 'cancelled']);
        return back()->with('success', 'Demande annulée.');
    }
}
