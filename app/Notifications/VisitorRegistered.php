<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitorRegistered extends Notification
{
    use Queueable;
    protected $visitor;

    /**
     * Create a new notification instance.
     */
    public function __construct($visitor)
    {
        $this->visitor = $visitor;
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
            ->line('📣 You\'re Invited!')
            ->line('Join us for an exciting CBN - Chhattisgarh Business Network meeting where entrepreneurs and professionals connect, collaborate, and grow together!')
            // ->line('🗓 Date: [Insert Date]')
            // ->line('🕘 Time: [Insert Time]')
            // ->line('📍 Venue: [Insert Venue Name & Address]')
            // ->line('💰 Meeting Fee: ₹500 (Including Breakfast)')
            ->line('This is a great opportunity to:')
            ->line('✅ Network with like-minded business owners')
            ->line('✅ Present your business to potential referrers')
            ->line('✅ Learn from fellow entrepreneurs')
            ->line('✅ Explore valuable business growth opportunities')
            ->line('Limited Seats!')
            ->line('Please confirm your attendance by replying to this message or calling [Insert Contact Number].')
            ->line('Let’s build a strong Chhattisgarhi business community together.')
            ->line('CBN – Local Business, Stronger Together.');
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
