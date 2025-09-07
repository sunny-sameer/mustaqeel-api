<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailNotification extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject($this->subject);

        // Split content by new lines and add each as a line
        $lines = explode("\n", $this->content);
        foreach ($lines as $line) {
            $mailMessage->line($line);
        }

        return $mailMessage;
    }
}
