<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResidentActivityNotification extends Notification
{
    use Queueable;

    /**
     * @param array{
     *   title:string,
     *   message:string,
     *   link:string,
     *   category?:string
     * } $payload
     */
    public function __construct(private array $payload) {}

    /**
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->payload['title'],
            'message' => $this->payload['message'],
            'link' => $this->payload['link'],
            'category' => $this->payload['category'] ?? 'resident_activity',
        ];
    }
}
