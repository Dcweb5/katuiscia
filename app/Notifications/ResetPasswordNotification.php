<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url('/reinitialisation/' . $this->token . '?email=' . urlencode($notifiable->email));

        $code = cache()->get('pwd_reset_code_' . $notifiable->email);
        if (!$code) {
            $code = sprintf("%06d", mt_rand(100000, 999999));
            cache()->put('pwd_reset_code_' . $notifiable->email, $code, 3600);
        }

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — KATUISCIA')
            ->greeting('Bonjour ' . ($notifiable->firstname ?? '') . ',')
            ->line('Vous avez demandé la réinitialisation de votre mot de passe KATUISCIA.')
            ->line('Pour réinitialiser votre mot de passe, vous pouvez au choix :')
            ->action('1. Cliquer sur ce lien de réinitialisation', $resetUrl)
            ->line('Ou')
            ->line('2. Saisir le code de validation suivant sur notre site :')
            ->line('**' . $code . '**')
            ->line('Ce code et ce lien de réinitialisation expirent dans 60 minutes.')
            ->salutation('Chaleureusement, l\'équipe KATUISCIA');
    }
}
