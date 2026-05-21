<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'rating', 'title', 'body', 'is_approved'];
    protected function casts(): array { return ['rating' => 'integer', 'is_approved' => 'boolean']; }
    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(\App\Modules\Product\Models\Product::class); }
}
