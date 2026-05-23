<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $fillable = ['order_id', 'invoice_number', 'file_path', 'public_token', 'total', 'is_emailed'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            if (empty($invoice->public_token)) {
                $invoice->public_token = Str::uuid();
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getDownloadUrlAttribute(): string
    {
        return url('/facture/' . $this->public_token);
    }
}
