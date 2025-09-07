<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitationNotification extends Notification
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
        // You can customize the channels here
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.user-invitation', [
                'content' => $this->content,
                'user' => $notifiable,
                'data' => $this->data
            ]);
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
