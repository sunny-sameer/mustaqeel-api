<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    use Queueable;

    protected $content;
    protected $subject;
    protected $data;
    protected $channel;

    public function __construct(string $content, string $subject = null, array $data = [], string $channel = null)
    {
        $this->content = $content;
        $this->subject = $subject;
        $this->data = $data;
        $this->channel = $channel;
    }

    abstract public function via($notifiable): array;

    public function toArray($notifiable): array
    {
        return [
            'message' => $this->content,
            'subject' => $this->subject,
            'data' => $this->data,
        ];
    }
}
