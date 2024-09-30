<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReactedToYourMessageNotification extends Notification
{
    use Queueable;

    private $user; // Authenticated user who reacted
    private $message; // The message that was reacted to

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $message)
    {
        // Here $user is the full user object
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            // Use the user object to access the user's name
            'message' => $this->user->name . ' has reacted to your message: "' . $this->message . '"',
        ];
    }
}
