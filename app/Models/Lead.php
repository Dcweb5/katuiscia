<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['firstname', 'email', 'quiz_responses', 'utm_source', 'utm_medium', 'utm_campaign', 'opted_in', 'consent_date', 'purchased', 'last_emailed_at'];
    protected function casts(): array { return ['quiz_responses' => 'array', 'opted_in' => 'boolean', 'purchased' => 'boolean', 'consent_date' => 'datetime', 'last_emailed_at' => 'datetime']; }
}
