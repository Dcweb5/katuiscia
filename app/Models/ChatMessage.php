<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['session_id', 'user_id', 'visitor_name', 'message', 'reply', 'is_read'];
    protected function casts(): array { return ['is_read' => 'boolean']; }
    public function user() { return $this->belongsTo(User::class); }
}
