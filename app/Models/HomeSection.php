<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = ['name', 'type', 'product_ids', 'collection_ids', 'large_type', 'large_product_id', 'large_collection_id', 'order', 'is_active', 'title', 'subtitle'];
    protected function casts(): array { return ['product_ids' => 'array', 'collection_ids' => 'array', 'large_product_id' => 'integer', 'large_collection_id' => 'integer', 'is_active' => 'boolean', 'order' => 'integer']; }
}
