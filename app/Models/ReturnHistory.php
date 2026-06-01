<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnHistory extends Model
{
    protected $table = 'return_histories';

    protected $fillable = ['return_request_id', 'status', 'comment'];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }
}
