<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        $code = cache()->get('email_verify_code_' . $notifiable->getKey());
        if (!$code) {
            $code = sprintf("%06d", mt_rand(100000, 999999));
            cache()->put('email_verify_code_' . $notifiable->getKey(), $code, 3600);
        }

        return (new MailMessage)
            ->subject('Confirmez votre adresse e-mail — KATUISCIA')
            ->greeting('Bonjour ' . ($notifiable->firstname ?? 'cher client') . ',')
            ->line('Merci de vous être inscrit(e) sur KATUISCIA.')
            ->line('Pour activer votre compte, vous pouvez au choix :')
            ->action('1. Cliquer sur ce lien de confirmation', $verifyUrl)
            ->line('Ou')
            ->line('2. Saisir le code de vérification suivant sur notre site :')
            ->line('**' . $code . '**')
            ->line('Ce code et ce lien de confirmation expirent dans 60 minutes.')
            ->salutation('Chaleureusement, l\'équipe KATUISCIA');
    }
}
