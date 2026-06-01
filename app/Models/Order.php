<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'cart_id', 'order_number', 'email',
        'firstname', 'lastname', 'address', 'address2',
        'postal_code', 'city', 'country', 'region', 'phone',
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

        static::created(function ($order) {
            $order->histories()->create([
                'status' => $order->status,
                'comment' => 'Commande créée et initialisée.',
            ]);
        });

        static::updated(function ($order) {
            if ($order->wasChanged('status')) {
                $statusLabels = [
                    'pending' => 'En attente de paiement',
                    'confirmed' => 'Commande confirmée',
                    'preparing' => 'Commande en préparation',
                    'shipped' => 'Commande expédiée',
                    'delivered' => 'Commande livrée',
                    'cancelled' => 'Commande annulée',
                ];
                $label = $statusLabels[$order->status] ?? $order->status;
                $order->histories()->create([
                    'status' => $order->status,
                    'comment' => "Statut changé en : {$label}.",
                ]);
            }
            if ($order->wasChanged('tracking_number') && $order->tracking_number) {
                $order->histories()->create([
                    'status' => $order->status,
                    'comment' => "Numéro de suivi ajouté : {$order->tracking_number}.",
                ]);
            }
        });
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class)->orderBy('created_at', 'desc');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(\App\Models\Invoice::class);
    }
}
