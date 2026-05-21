<?php

namespace App\Modules\Product\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Category::withCount('products');

        if ($request->boolean('active')) {
            $query->active();
        }

        if ($request->boolean('parents_only')) {
            $query->whereNull('parent_id');
        }

        $categories = $query->ordered()->get();

        return ApiResponse::success(
            data: CategoryResource::collection($categories)
        );
    }

    public function show(string $slug): JsonResponse
    {
        $category = Category::with(['products' => fn($q) => $q->active()->ordered(), 'children'])
            ->where('slug', $slug)
            ->firstOrFail();

        return ApiResponse::success(
            data: new CategoryResource($category)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:categories,slug',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category = Category::create($validated);

        return ApiResponse::success(
            data: new CategoryResource($category),
            message: 'Catégorie créée avec succès.',
            code: 201
        );
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update($validated);

        return ApiResponse::success(
            data: new CategoryResource($category->fresh()),
            message: 'Catégorie mise à jour avec succès.'
        );
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->products()->exists()) {
            return ApiResponse::error(
                message: 'Impossible de supprimer : des produits sont liés à cette catégorie.',
                code: 400
            );
        }

        $category->delete();

        return ApiResponse::success(
            message: 'Catégorie supprimée avec succès.'
        );
    }
}
