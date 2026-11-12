<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DySignNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public ?string $url = null,
        public string $category = 'System'
    ) {
    }

    /**
     * Store notifications in the database.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Notification data displayed in the navbar.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url ?? '/dashboard',
            'category' => $this->category,
        ];
    }
}
