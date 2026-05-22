<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = ['name', 'type', 'product_ids', 'collection_ids', 'order', 'is_active', 'title', 'subtitle'];
    protected function casts(): array { return ['product_ids' => 'array', 'collection_ids' => 'array', 'is_active' => 'boolean', 'order' => 'integer']; }
}
