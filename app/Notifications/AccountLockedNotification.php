<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountLockedNotification extends Notification
{
    use Queueable;

    protected object $user;
    protected int $hours;

    public function __construct(object $user, int $hours = 24)
    {
        $this->user = $user;
        $this->hours = $hours;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Sécurité : Votre compte KATUISCIA a été verrouillé')
            ->greeting('Bonjour ' . ($this->user->firstname ?? 'cher client') . ',')
            ->line('Nous vous informons que votre compte KATUISCIA a été temporairement verrouillé pour une durée de ' . $this->hours . ' heures.')
            ->line('Cette mesure a été déclenchée suite à 5 tentatives consécutives de connexion avec un mot de passe incorrect.')
            ->line('Pour des raisons de sécurité, l\'accès à votre compte est bloqué afin de protéger vos données personnelles.')
            ->line('Si vous êtes à l\'origine de ces tentatives et avez oublié votre mot de passe, vous pourrez utiliser la fonction de réinitialisation une fois le compte débloqué ou contacter l\'administration.')
            ->line('Si vous n\'êtes pas à l\'origine de ces tentatives, veuillez contacter immédiatement notre équipe d\'assistance à contact@katuiscia.com pour sécuriser votre compte.')
            ->salutation('Cordialement, l\'équipe de sécurité KATUISCIA');
    }
}
