<?php
namespace App\Http\Controllers;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string|max:2000',
        ]);

        $exists = Review::where('user_id', auth()->id())->where('product_id', $request->product_id)->exists();
        if ($exists) return back()->with('error', 'Vous avez déjà laissé un avis sur ce produit.');

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        // +50 pts fidélité à l'approbation (pas immédiatement)

        return back()->with('success', 'Avis soumis ! Il sera visible après validation.');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) abort(403);
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string|max:2000',
        ]);
        $review->update($request->only('rating','title','body'));
        return back()->with('success', 'Avis modifié.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) abort(403);
        $review->delete();
        return back()->with('success', 'Avis supprimé.');
    }
}
