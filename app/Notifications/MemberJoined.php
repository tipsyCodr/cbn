<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberJoined extends Notification
{
    use Queueable;
    protected $user;
    protected $un_en_password;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $un_en_password)
    {
        $this->user = $user;
        $this->un_en_password = $un_en_password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Welcome to CBN Group.')
            ->line('Here are your login credentials:')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->un_en_password)
            ->action('App Link for Logging in', url('https://play.google.com/store/apps/details?id=com.cbn.app'))
            ->line('Thank you for registering as a member!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
