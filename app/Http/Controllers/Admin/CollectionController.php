<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Modules\Product\Models\Product;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::with(['products', 'category'])->orderBy('created_at', 'desc')->get();
        $products = Product::active()->ordered()->get();
        $categories = \App\Modules\Product\Models\Category::active()->ordered()->get();
        $active = Collection::where('is_active', true)->count();
        $drafts = Collection::where('is_active', false)->count();

        return view('admin.collections.index', compact('collections', 'products', 'categories', 'active', 'drafts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = ImageOptimizer::store($request->file('image'), 'collections', 1000);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $collection = Collection::create($validated);

        if ($request->has('product_ids')) {
            $pivot = [];
            foreach ($request->product_ids as $pid) {
                $pivot[$pid] = ['quantity' => 1];
            }
            $collection->products()->sync($pivot);
        }

        return redirect()->route('admin.collections.index')->with('success', 'Collection créée.');
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = ImageOptimizer::store($request->file('image'), 'collections', 1000);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $collection->update($validated);

        $pivot = [];
        if ($request->has('product_ids')) {
            foreach ($request->product_ids as $pid) {
                $pivot[$pid] = ['quantity' => 1];
            }
        }
        $collection->products()->sync($pivot);

        return redirect()->route('admin.collections.index')->with('success', 'Collection mise à jour.');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return back()->with('success', 'Collection supprimée.');
    }

    public function toggle(Collection $collection)
    {
        $collection->update(['is_active' => !$collection->is_active]);
        return back()->with('success', $collection->is_active ? 'Collection activée.' : 'Collection désactivée.');
    }
}
