<?php
namespace App\Http\Controllers;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request) {
        $q = $request->query('q');
        if (!$q || strlen($q) < 2) return view('pages.search', ['products' => collect(), 'query' => '']);
        
        $products = Product::with(['categories', 'images'])->active()
            ->where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->orderBy('name')->paginate(20);
        
        return view('pages.search', compact('products', 'q'));
    }
}
