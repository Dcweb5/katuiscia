<?php

namespace App\Modules\Product\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'long_description' => $this->long_description,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'display_price' => $this->display_price,
            'has_discount' => $this->has_discount,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'badge' => $this->badge,
            'need' => $this->need,
            'image_primary' => $this->image_primary,
            'image_secondary' => $this->image_secondary,
            'ingredients' => $this->ingredients,
            'size' => $this->size,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'created_at' => $this->created_at,
        ];
    }
}
