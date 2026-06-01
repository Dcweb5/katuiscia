<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['user', 'order', 'item', 'images', 'histories'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->paginate(20);

        $stats = [
            'pending' => ReturnRequest::where('status', 'pending')->count(),
            'approved' => ReturnRequest::where('status', 'approved')->count(),
            'received' => ReturnRequest::where('status', 'received')->count(),
            'completed' => ReturnRequest::where('status', 'completed')->count(),
        ];

        return view('admin.returns.index', compact('returns', 'stats'));
    }

    public function update(Request $request, ReturnRequest $return)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,received,completed,rejected,cancelled',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $return->update($validated);
        return back()->with('success', 'Retour #' . $return->request_number . ' mis à jour.');
    }
}
