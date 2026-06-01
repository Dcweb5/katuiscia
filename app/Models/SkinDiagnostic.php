<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkinDiagnostic extends Model
{
    protected $table = 'skin_diagnostics';

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'skin_type', 'concern',
        'current_products', 'image_path', 'analysis_result'
    ];

    protected $casts = [
        'analysis_result' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
