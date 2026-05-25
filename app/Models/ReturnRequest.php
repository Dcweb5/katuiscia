<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReturnRequest extends Model
{
    protected $table = 'return_requests';
    protected $fillable = ['user_id', 'order_id', 'order_item_id', 'request_number', 'type', 'reason', 'status', 'admin_notes'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($r) {
            if (empty($r->request_number)) {
                $r->request_number = 'RET-' . strtoupper(Str::random(6));
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function item() { return $this->belongsTo(OrderItem::class, 'order_item_id'); }
    public function images() { return $this->hasMany(ReturnImage::class); }
}
