<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ShippingZone extends Model
{
    protected $fillable = [
        'name',
        'code',
        'is_active',
        'price',
        'delivery_time',
    ];
 
    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
    ];
}
