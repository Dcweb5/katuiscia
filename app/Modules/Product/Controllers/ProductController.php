<?php

namespace App\Modules\Product\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('categories')->active();

        // Filtre par catégorie (slug)
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Filtre par besoin (hydratation, eclat, restauration)
        if ($request->has('need')) {
            $query->byNeed($request->need);
        }

        // Filtre par prix min/max
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filtre featured
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Tri
        $sort = $request->get('sort', 'order');
        $order = $request->get('order', 'asc');
        $allowedSorts = ['name', 'price', 'created_at', 'order'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $order === 'desc' ? 'desc' : 'asc');
        } else {
            $query->ordered();
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::with('categories')->where('slug', $slug)->firstOrFail();

        return ApiResponse::success(
            data: new ProductResource($product)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|gt:price',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'stock' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'need' => 'nullable|string|max:50',
            'image_primary' => 'nullable|string|max:500',
            'image_secondary' => 'nullable|string|max:500',
            'ingredients' => 'nullable|string',
            'size' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product = Product::create($validated);

        // Associer les catégories
        if (!empty($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }

        return ApiResponse::success(
            data: new ProductResource($product->load('categories')),
            message: 'Produit créé avec succès.',
            code: 201
        );
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|gt:price',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'stock' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'need' => 'nullable|string|max:50',
            'image_primary' => 'nullable|string|max:500',
            'image_secondary' => 'nullable|string|max:500',
            'ingredients' => 'nullable|string',
            'size' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        if (isset($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }

        return ApiResponse::success(
            data: new ProductResource($product->fresh()->load('categories')),
            message: 'Produit mis à jour avec succès.'
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->categories()->detach();
        $product->delete();

        return ApiResponse::success(
            message: 'Produit supprimé avec succès.'
        );
    }
}
