<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'type', 'company_name', 'siret',
        'source', 'message', 'preferred_date', 'preferred_time',
        'status', 'appointment_date', 'appointment_time', 'admin_instructions'
    ];
}
