<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnImage extends Model
{
    protected $fillable = ['return_request_id', 'path'];
    public function returnRequest() { return $this->belongsTo(ReturnRequest::class); }
}
