<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminMessage extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $subject,
        public readonly string $body,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Hi '.$notifiable->name.',')
            ->line($this->body)
            ->line('This message was sent by an administrator.');
    }
}
