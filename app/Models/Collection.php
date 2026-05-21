<?php
namespace App\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Collection extends Model
{
    protected $table = 'collections';

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'original_price',
        'image', 'category_id', 'is_active', 'order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'original_price' => 'float',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = static::uniqueSlug(Str::slug($collection->name));
            }
        });
        static::updating(function ($collection) {
            if ($collection->isDirty('name') && !$collection->isDirty('slug')) {
                $collection->slug = static::uniqueSlug(Str::slug($collection->name), $collection->id);
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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product')
                    ->withPivot('quantity');
    }

    public function category()
    {
        return $this->belongsTo(\App\Modules\Product\Models\Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http') || str_starts_with($this->image, 'assets/')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getDiscountPercentAttribute()
    {
        if (!$this->original_price || $this->original_price <= $this->price) return 0;
        return round((($this->original_price - $this->price) / $this->original_price) * 100);
    }
}
