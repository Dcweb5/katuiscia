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

        if ($period = $request->query('period')) {
            $days = match($period) { 'today' => 1, '7' => 7, '30' => 30, default => null };
            if ($days) $query->where('created_at', '>=', now()->subDays($days));
        }
        match ($request->query('sort')) { 'oldest' => $query->reorder()->orderBy('created_at'), 'rating' => $query->reorder()->orderByDesc('rating'), default => null };

        $reviews = $query->paginate(20)->appends($request->query());
        $pending = Review::where('is_approved', false)->count();
        $approved = Review::where('is_approved', true)->count();
        return view('admin.reviews.index', compact('reviews','pending','approved'));
    }
    public function approve(Review $review) { $review->update(['is_approved' => true]); $review->user->increment('loyalty_points', 50); return back()->with('success','Avis approuvé. +50 pts fidélité.'); }
    public function destroy(Review $review) { $review->delete(); return back()->with('success','Avis supprimé.'); }
}
