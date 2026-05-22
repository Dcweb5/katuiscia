<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\HomeSection;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::orderBy('type')->get()->keyBy('type');
        $products = Product::active()->ordered()->get();
        $collections = Collection::active()->get();
        $categories = Category::active()->ordered()->get();

        $heroProducts = $products->whereIn('id', $sections->get('hero')?->product_ids ?? []);
        $selectionProducts = $products->whereIn('id', $sections->get('selection')?->product_ids ?? []);
        $selectionCollections = $collections->whereIn('id', $sections->get('selection')?->collection_ids ?? []);

        // Top products pour info
        try {
            $mostSold = Product::mostSold(5)->get();
        } catch (\Exception $e) {
            $mostSold = collect();
        }
        $latestProduct = Product::latestPublished(1)->first();

        return view('admin.sections.index', compact(
            'sections', 'products', 'collections', 'categories',
            'heroProducts', 'selectionProducts', 'selectionCollections',
            'mostSold', 'latestProduct'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hero_product_ids' => 'nullable|array|max:5',
            'hero_product_ids.*' => 'exists:products,id',
            'selection_product_ids' => 'nullable|array|max:4',
            'selection_product_ids.*' => 'exists:products,id',
            'selection_collection_ids' => 'nullable|array|max:4',
            'selection_collection_ids.*' => 'exists:collections,id',
        ]);

        // Hero
        HomeSection::updateOrCreate(
            ['type' => 'hero'],
            ['name' => 'Hero', 'product_ids' => $request->hero_product_ids ?? [], 'is_active' => true]
        );

        // Selection
        HomeSection::updateOrCreate(
            ['type' => 'selection'],
            [
                'name' => 'Sélection Organisée',
                'product_ids' => $request->selection_product_ids ?? [],
                'collection_ids' => $request->selection_collection_ids ?? [],
                'is_active' => true
            ]
        );

        return back()->with('success', 'Sections mises à jour.');
    }
}
