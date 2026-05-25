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
        $returns = ReturnRequest::with(['order', 'item', 'images'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
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
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        if ($order->user_id !== auth()->id()) abort(403);

        $returnRequest = ReturnRequest::create([
            'user_id' => auth()->id(),
            'order_id' => $validated['order_id'],
            'order_item_id' => $validated['order_item_id'] ?? null,
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Upload images (max 5)
        if ($request->hasFile('images')) {
            foreach (array_slice($request->file('images'), 0, 5) as $file) {
                $path = \App\Services\ImageOptimizer::store($file, 'returns', 1600);
                \App\Models\ReturnImage::create([
                    'return_request_id' => $returnRequest->id,
                    'path' => $path,
                ]);
            }
        }

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
