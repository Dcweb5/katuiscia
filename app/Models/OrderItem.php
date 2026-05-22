<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name',
        'price', 'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'quantity' => 'integer',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Modules\Product\Models\Product::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
