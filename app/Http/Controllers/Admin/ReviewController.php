<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request) {
        $query = Review::with(['user','product'])->orderBy('created_at','desc');

        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title','like',"%{$search}%")->orWhere('body','like',"%{$search}%")
                  ->orWhereHas('user',fn($u)=>$u->where('firstname','like',"%{$search}%")->orWhere('lastname','like',"%{$search}%"))
                  ->orWhereHas('product',fn($p)=>$p->where('name','like',"%{$search}%"));
            });
        }
        if ($request->query('status') === 'pending') $query->where('is_approved', false);
        elseif ($request->query('status') === 'approved') $query->where('is_approved', true);

        if ($from = $request->query('date_from')) {
            $query->where('created_at', '>=', $from . ' 00:00:00');
        }
        if ($to = $request->query('date_to')) {
            $query->where('created_at', '<=', $to . ' 23:59:59');
        }
        match ($request->query('sort')) { 'oldest' => $query->reorder()->orderBy('created_at'), 'rating' => $query->reorder()->orderByDesc('rating'), default => null };

        $reviews = $query->paginate(20)->appends($request->query());
        $pending = Review::where('is_approved', false)->count();
        $approved = Review::where('is_approved', true)->count();
        return view('admin.reviews.index', compact('reviews','pending','approved'));
    }
    public function approve(Review $review) {
        $review->update(['is_approved' => true]);
        
        $points = 0;
        if (\App\Services\LoyaltyService::isEnabled() && $review->user) {
            $points = (int) \App\Models\Setting::get('loyalty_points_per_review', 50);
            if ($points > 0) {
                \App\Services\LoyaltyService::addPoints(
                    $review->user,
                    $points,
                    'review',
                    'Avis approuvé sur le produit : ' . ($review->product ? $review->product->name : 'Produit')
                );
            }
        }
        
        $message = 'Avis approuvé.';
        if ($points > 0) {
            $message .= " +{$points} pts fidélité.";
        }
        
        return back()->with('success', $message);
    }
    public function destroy(Review $review) { $review->delete(); return back()->with('success','Avis supprimé.'); }
}
