<?php



namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification
{
    use Queueable;

    protected $content;
    protected $subject;
    protected $channel;
    protected $data;

    public function __construct(string $content, string $subject = null, string $channel = 'database', array $data = [])
    {
        $this->content = $content;
        $this->subject = $subject;
        $this->channel = $channel;
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return [$this->channel];
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
