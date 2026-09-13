<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemAlertNotification extends Notification
{
    use Queueable;

    protected array $payload;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $payload)
    {
        // Expecting keys like: 'title', 'message', 'icon', 'action_url'
        $this->payload = $payload;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Tells Laravel to save this directly to the database table
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'      => $this->payload['title'] ?? 'System Notice',
            'message'    => $this->payload['message'] ?? '',
            'icon'       => $this->payload['icon'] ?? 'fas fa-info-circle',
            'action_url' => $this->payload['action_url'] ?? '#',
        ];
    }
}
