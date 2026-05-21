<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'firstname', 'lastname', 'name', 'email', 'password',
    'phone', 'city', 'postal_code', 'country',
    'newsletter', 'birthday', 'avatar',
    'is_admin', 'is_active', 'loyalty_points',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'newsletter' => 'boolean',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'birthday' => 'date',
            'loyalty_points' => 'integer',
        ];
    }

    public function getFullNameAttribute(): string
    {
        $full = trim($this->firstname . ' ' . $this->lastname);
        return $full ?: ($this->name ?? 'Utilisateur');
    }

    public function sendPasswordResetNotification($token): void
    {
        cache()->put('pwd_reset_raw_' . $this->email, $token, 3600);
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    public function reviews() { return $this->hasMany(Review::class); }
}
