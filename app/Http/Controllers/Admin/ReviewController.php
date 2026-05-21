<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index() {
        $reviews = Review::with(['user','product'])->orderBy('created_at','desc')->paginate(20);
        $pending = Review::where('is_approved', false)->count();
        $approved = Review::where('is_approved', true)->count();
        return view('admin.reviews.index', compact('reviews','pending','approved'));
    }
    public function approve(Review $review) {
        $review->update(['is_approved' => true]);
        $review->user->increment('loyalty_points', 50);
        return back()->with('success', 'Avis approuvé. +50 pts fidélité.');
    }
    public function destroy(Review $review) { $review->delete(); return back()->with('success','Avis supprimé.'); }
}
