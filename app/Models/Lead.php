<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['firstname', 'email', 'quiz_responses', 'utm_source', 'utm_medium', 'utm_campaign', 'opted_in', 'consent_date', 'purchased', 'total_revenue', 'orders_count', 'last_emailed_at', 'email_sequence_step'];
    protected function casts(): array { return ['quiz_responses' => 'array', 'opted_in' => 'boolean', 'purchased' => 'boolean', 'total_revenue' => 'float', 'orders_count' => 'integer', 'consent_date' => 'datetime', 'last_emailed_at' => 'datetime']; }
}
