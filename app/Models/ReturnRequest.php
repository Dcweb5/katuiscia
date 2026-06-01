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

        static::created(function ($return) {
            $return->histories()->create([
                'status' => $return->status,
                'comment' => 'Demande de retour créée.',
            ]);
        });

        static::updated(function ($return) {
            if ($return->wasChanged('status')) {
                $statusLabels = [
                    'pending' => 'En attente de traitement',
                    'approved' => 'Retour approuvé',
                    'received' => 'Colis de retour reçu',
                    'completed' => 'Retour traité et complété',
                    'rejected' => 'Retour rejeté',
                    'cancelled' => 'Retour annulé',
                ];
                $label = $statusLabels[$return->status] ?? $return->status;
                $return->histories()->create([
                    'status' => $return->status,
                    'comment' => "Statut de retour changé en : {$label}.",
                ]);
            }
            if ($return->wasChanged('admin_notes') && $return->admin_notes) {
                $return->histories()->create([
                    'status' => $return->status,
                    'comment' => "Note administrateur ajoutée : " . Str::limit($return->admin_notes, 100),
                ]);
            }
        });
    }

    public function histories()
    {
        return $this->hasMany(ReturnHistory::class)->orderBy('created_at', 'desc');
    }

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function item() { return $this->belongsTo(OrderItem::class, 'order_item_id'); }
    public function images() { return $this->hasMany(ReturnImage::class); }
}
