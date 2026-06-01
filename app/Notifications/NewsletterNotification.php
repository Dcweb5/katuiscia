<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterNotification extends Notification
{
    use Queueable;

    public string $subject;
    public string $content;
    public string $email;

    public function __construct(string $subject, string $content, string $email)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->email = $email;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $unsubscribeUrl = url('/newsletter/desabonnement/' . urlencode($this->email));

        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.newsletter', [
                'subject' => $this->subject,
                'content' => $this->content,
                'unsubscribeUrl' => $unsubscribeUrl,
            ]);
    }
}
