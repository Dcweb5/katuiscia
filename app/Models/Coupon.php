<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount',
        'max_uses', 'used_count', 'starts_at', 'expires_at',
        'is_active', 'description', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'float',
            'min_order_amount' => 'float',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(float $cartTotal): bool
    {
        if (!$this->is_active) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->min_order_amount && $cartTotal < $this->min_order_amount) return false;
        return true;
    }

    public function calculateDiscount(float $cartTotal): float
    {
        return match ($this->type) {
            'fixed' => min($this->value ?? 0, $cartTotal),
            'free_shipping' => 0,
            default => round($cartTotal * ($this->value ?? 0) / 100, 2), // percentage
        };
    }

    public function getDiscountLabel(): string
    {
        return match ($this->type) {
            'free_shipping' => 'Livraison offerte',
            'fixed' => number_format($this->value ?? 0, 2, ',', ' ') . ' € de réduction',
            default => $this->value . '% de réduction',
        };
    }
}
