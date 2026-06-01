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
    'login_attempts', 'locked_until',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    public function sendEmailVerificationNotification(): void
    {
        try {
            $this->notify(new \App\Notifications\VerifyEmailNotification());
        } catch (\Exception $e) {
            \Log::error('Failed to send email verification notification: ' . $e->getMessage());
            session()->flash('email_error', 'L\'e-mail de confirmation n\'a pas pu être envoyé suite à un problème de connexion au serveur de messagerie.');
        }
    }

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
            'login_attempts' => 'integer',
            'locked_until' => 'datetime',
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
        try {
            $this->notify(new \App\Notifications\ResetPasswordNotification($token));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset notification: ' . $e->getMessage());
            session()->flash('email_error', 'L\'e-mail de réinitialisation n\'a pas pu être envoyé suite à un problème de connexion au serveur de messagerie.');
        }
    }

    public function reviews() { return $this->hasMany(Review::class); }
}
