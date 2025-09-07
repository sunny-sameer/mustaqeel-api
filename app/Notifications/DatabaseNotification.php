<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DatabaseNotification extends Notification
{
    use Queueable;

    protected $content;
    protected $subject;
    protected $data;

    public function __construct(string $content, string $subject = null, array $data = [])
    {
        $this->content = $content;
        $this->subject = $subject;
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => $this->content,
            'subject' => $this->subject,
            'data' => $this->data,
        ];
    }
}
