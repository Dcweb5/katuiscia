<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'long_description',
        'price', 'sale_price', 'sku', 'stock',
        'badge', 'need',
        'image_primary', 'image_secondary',
        'ingredients', 'size',
        'order', 'is_active', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'sale_price' => 'float',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function images(): HasMany
    {
        return $this->hasMany(\App\Models\ProductImage::class);
    }

    public function reviews() { return $this->hasMany(\App\Models\Review::class); }

    public function collections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Collection::class, 'collection_product')->withPivot('quantity');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $slug)
    {
        return $query->whereHas('categories', fn($q) => $q->where('slug', $slug));
    }

    public function scopeByNeed($query, $need)
    {
        return $query->where('need', $need);
    }

    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max ?? 999999]);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function scopeMostSold($query, $limit = null, $excludeIds = [])
    {
        $q = $query->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['confirmed', 'preparing', 'shipped', 'delivered'])
            ->select('products.*', \DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id')
            ->orderByDesc('total_sold');

        if (!empty($excludeIds)) {
            $q->whereNotIn('products.id', $excludeIds);
        }
        if ($limit) {
            $q->limit($limit);
        }
        return $q;
    }

    public function scopeLatestPublished($query, $limit = 1)
    {
        return $query->active()->where('is_active', true)->latest()->limit($limit);
    }

    public function getImageUrlAttribute(): string
    {
        $path = $this->image_primary;
        if (!$path) {
            return asset('assets/images/K ICONE.webp');
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }
        if (str_starts_with($path, '/storage/')) {
            return asset(ltrim($path, '/'));
        }
        return asset('storage/' . $path);
    }

    public function getDisplayPriceAttribute(): string
    {
        return number_format($this->final_price, 0, ',', ' ') . ' €';
    }

    public function getFinalPriceAttribute(): float
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return $this->sale_price;
        }
        return $this->price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }
}
