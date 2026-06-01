<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'long_description',
        'price', 'sale_price', 'sku', 'stock',
        'badge', 'need',
        'image_primary', 'image_secondary',
        'ingredients', 'size',
        'order', 'is_active', 'is_featured',
        'key_ingredients', 'application_ritual', 'promo_expires_at',
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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $base = empty($product->slug) ? Str::slug($product->name) : $product->slug;
            $product->slug = static::uniqueSlug($base);
        });
        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $base = $product->isDirty('slug') ? $product->slug : Str::slug($product->name);
                $product->slug = static::uniqueSlug($base, $product->id);
            }
        });
    }

    public static function uniqueSlug($base, $excludeId = null)
    {
        $slug = $base;
        $counter = 1;
        while (true) {
            $query = static::where('slug', $slug);
            if ($excludeId) $query->where('id', '!=', $excludeId);
            if (!$query->exists()) break;
            $slug = $base . '-' . ++$counter;
        }
        return $slug;
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
        return number_format($this->final_price, 2, ',', ' ') . ' €';
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price > $this->price;
    }
}
