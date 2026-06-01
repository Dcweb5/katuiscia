<?php
namespace App\Http\Controllers;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request) {
        $q = $request->query('q');
        if (!$q || strlen($q) < 2) return view('pages.search', ['products' => collect(), 'q' => '']);
        
        // Split the search query into individual keywords
        $keywords = array_filter(explode(' ', $q), function($word) {
            return strlen(trim($word)) >= 2;
        });

        $products = Product::with(['categories', 'images'])->active()
            ->where(function($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $cleanWord = trim($word);
                    // Basic singularization: strip trailing 's' or 'x' if word length > 3
                    if (strlen($cleanWord) > 3 && (str_ends_with(strtolower($cleanWord), 's') || str_ends_with(strtolower($cleanWord), 'x'))) {
                        $cleanWord = substr($cleanWord, 0, -1);
                    }
                    $query->where(function($sub) use ($cleanWord) {
                        $sub->where('name', 'like', "%{$cleanWord}%")
                            ->orWhere('description', 'like', "%{$cleanWord}%");
                    });
                }
            })
            ->orderBy('name')->paginate(20);
        
        return view('pages.search', compact('products', 'q'));
    }
}
