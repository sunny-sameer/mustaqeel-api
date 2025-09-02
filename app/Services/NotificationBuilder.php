<?php

namespace App\Services;

use App\Notifications\DynamicNotification;

class NotificationBuilder
{
    protected $event;
    protected $notifiable;
    protected $data = [];

    public function on(string $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function with($notifiable, array $data = []): self
    {
        $this->notifiable = $notifiable;
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function send(string $notificationClass = null)
    {

        $notificationClass = $notificationClass ?? DynamicNotification::class;

        $notification = new $notificationClass($this->event, $this->data);

        return $this->notifiable->notify($notification);
    }
}
