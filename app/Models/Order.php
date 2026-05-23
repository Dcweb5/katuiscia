<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'email',
        'firstname', 'lastname', 'address', 'address2',
        'postal_code', 'city', 'country', 'phone',
        'subtotal', 'discount', 'shipping', 'total',
        'coupon_code', 'status', 'stripe_session_id', 'payment_status', 'paid_at', 'payment_gateway',
        'tracking_number', 'payment_method', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'float',
            'discount' => 'float',
            'shipping' => 'float',
            'total' => 'float',
            'paid_at' => 'datetime',
        ];
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'KAT-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
