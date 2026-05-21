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

    public function getDisplayPriceAttribute(): string
    {
        return number_format($this->sale_price ?? $this->price, 0, ',', ' ') . ' €';
    }

    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }
}
