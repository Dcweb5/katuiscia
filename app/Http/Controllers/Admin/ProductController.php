<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories', 'images')->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::ordered()->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::ordered()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'stock' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'need' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product = Product::create($validated);

        if (!empty($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }

        // Upload d'images
        if ($request->hasFile('images')) {
            $uploadedPaths = [];
            foreach ($request->file('images') as $i => $image) {
                $path = ImageOptimizer::store($image, 'products', 1200);
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => $i === 0,
                    'order' => $i,
                ]);
                $uploadedPaths[] = $path;
            }
            // Synchroniser les colonnes image_primary / image_secondary du produit
            if (!empty($uploadedPaths)) {
                $product->image_primary = Storage::url($uploadedPaths[0]);
                $product->image_secondary = isset($uploadedPaths[1]) ? Storage::url($uploadedPaths[1]) : Storage::url($uploadedPaths[0]);
                $product->save();
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $product->load('categories', 'images');
        $categories = Category::ordered()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'stock' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'need' => 'nullable|string|max:50',
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

        // Upload de nouvelles images
        if ($request->hasFile('images')) {
            $existingCount = $product->images()->count();
            foreach ($request->file('images') as $i => $image) {
                if ($existingCount + $i >= 5) break; // Max 5 images
                $path = ImageOptimizer::store($image, 'products', 1200);
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => $i === 0 && $product->images()->count() === 0,
                    'order' => $existingCount + $i,
                ]);
            }
            // Synchro image_primary / image_secondary après upload
            $latestImages = $product->images()->orderBy('order')->get();
            if ($latestImages->isNotEmpty()) {
                $product->image_primary = Storage::url($latestImages[0]->path);
                $product->image_secondary = $latestImages->count() > 1 ? Storage::url($latestImages[1]->path) : Storage::url($latestImages[0]->path);
                $product->save();
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        // Supprimer les images du stockage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $product->categories()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé.');
    }

    public function uploadImages(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $uploaded = [];
        $existingCount = $product->images()->count();

        foreach ($request->file('images') as $i => $image) {
            if ($existingCount + $i >= 5) break;
            $path = ImageOptimizer::store($image, 'products', 1200);
            $img = ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_primary' => $i === 0 && $existingCount === 0,
                'order' => $existingCount + $i,
            ]);
            $uploaded[] = $img;
        }

        $this->syncImageColumns($product);
        return redirect()->route('admin.products.edit', $product)->with('success', count($uploaded) . ' image(s) ajoutée(s).');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        $this->syncImageColumns($product);
        return redirect()->route('admin.products.edit', $product)->with('success', 'Image supprimée.');
    }

    public function setPrimary(Product $product, ProductImage $image)
    {
        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true, 'order' => 0]);

        // Reorder remaining images
        $others = $product->images()->where('id', '!=', $image->id)->orderBy('order')->get();
        foreach ($others as $i => $img) {
            $img->update(['order' => $i + 1]);
        }

        $this->syncImageColumns($product);
        return redirect()->route('admin.products.edit', $product)->with('success', 'Image principale définie.');
    }

    public function reorder(Request $request, Product $product)
    {
        $orderData = $request->input('order', []);
        foreach ($orderData as $item) {
            ProductImage::where('id', $item['id'])
                ->where('product_id', $product->id)
                ->update(['order' => $item['order']]);
        }
        $this->syncImageColumns($product);
        return response()->json(['success' => true]);
    }

    public function moveUp(Product $product, ProductImage $image)
    {
        $prevImage = $product->images()->where('order', '<', $image->order)->orderBy('order', 'desc')->first();
        if ($prevImage) {
            $tmpOrder = $image->order;
            $image->update(['order' => $prevImage->order]);
            $prevImage->update(['order' => $tmpOrder]);
        }
        $this->syncImageColumns($product);
        return redirect()->route('admin.products.edit', $product);
    }

    public function moveDown(Product $product, ProductImage $image)
    {
        $nextImage = $product->images()->where('order', '>', $image->order)->orderBy('order', 'asc')->first();
        if ($nextImage) {
            $tmpOrder = $image->order;
            $image->update(['order' => $nextImage->order]);
            $nextImage->update(['order' => $tmpOrder]);
        }
        $this->syncImageColumns($product);
        return redirect()->route('admin.products.edit', $product);
    }

    private function syncImageColumns(Product $product): void
    {
        $images = $product->images()->orderBy('order')->get();
        if ($images->isNotEmpty()) {
            $product->image_primary = $this->imagePathToUrl($images[0]->path);
            $product->image_secondary = $images->count() > 1
                ? $this->imagePathToUrl($images[1]->path)
                : $this->imagePathToUrl($images[0]->path);
            $product->save();
        }
    }

    private function imagePathToUrl(string $path): string
    {
        // Si le chemin commence par http, assets/, ou est déjà une URL publique, on le retourne tel quel
        if (str_starts_with($path, 'http') || str_starts_with($path, 'assets/') || str_starts_with($path, '/')) {
            return $path;
        }
        // Sinon c'est un chemin de storage (ex: products/abc123.jpg)
        return Storage::url($path);
    }
}
