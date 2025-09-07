<?php

namespace App\Services\V1\Notifications;


use App\Models\NotificationEvent;
use App\Models\NotificationTemplate;
use App\Models\EmailNotifiable;
use App\Services\MailMergeService;
use Illuminate\Support\Facades\Notification;

class NotificationBuilder
{
    protected $eventName;
    protected $notifiable;
    protected $data = [];
    protected $channel;

    public function on(string $eventName): self
    {
        $this->eventName = $eventName;
        return $this;
    }

    public function with($notifiable, array $data = []): self
    {
        // If a string is provided, assume it's an email address
        if (is_string($notifiable)) {
            $this->notifiable = new EmailNotifiable($notifiable);
        } else {
            $this->notifiable = $notifiable;
        }

        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function via(string $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    public function send(string $notificationClass = null)
    {
        // Check if we have a notifiable entity
        if (!$this->notifiable) {
            throw new \Exception("No notifiable entity provided. Use the with() method to specify a recipient.");
        }

        // Get the event
        $event = NotificationEvent::where('name', $this->eventName)->first();

        if (!$event) {
            throw new \Exception("Notification event '{$this->eventName}' not found");
        }

        // Get templates for this event
        $templates = $event->templates;

        if ($templates->isEmpty()) {
            throw new \Exception("No templates found for event '{$this->eventName}'");
        }

        // If a specific channel was requested, filter templates
        if ($this->channel) {
            $templates = $templates->where('type', $this->channel);

            if ($templates->isEmpty()) {
                throw new \Exception("No templates found for channel '{$this->channel}' in event '{$this->eventName}'");
            }
        }

        // Process each template and send notification
        foreach ($templates as $template) {
            $this->sendNotificationForTemplate($template, $notificationClass);
        }

        return true;
    }

    protected function sendNotificationForTemplate(NotificationTemplate $template, string $notificationClass = null)
    {
        $mergeService = new MailMergeService();

        // Prepare content with replaced placeholders
        $content = $template->custom_content ?? $template->default_content;
        $processedContent = $mergeService->replacePlaceholders($content, $this->data);

        $subject = $template->subject;
        if ($subject) {
            $subject = $mergeService->replacePlaceholders($subject, $this->data);
        }

        // Determine notification class to use
        $notificationClass = $notificationClass ?? $this->getDefaultNotificationClass($template->type);

        // Create notification instance with correct parameters
        $notification = new $notificationClass($processedContent, $subject, $this->data);

        // Send notification differently based on the type of notifiable
        if ($this->notifiable instanceof EmailNotifiable) {
            // Use Laravel's Notification facade for email notifiables
            Notification::route($template->type, $this->notifiable->routeNotificationForMail($notification))
                ->notify($notification);
        } else {
            // Use the notifiable's notify method for Eloquent models
            $this->notifiable->notify($notification);
        }
    }

    protected function getDefaultNotificationClass(string $channel): string
    {
        $map = [
            'mail' => \App\Notifications\MailNotification::class,
            // 'sms' => \App\Notifications\SmsNotification::class,
            'database' => \App\Notifications\DatabaseNotification::class,
        ];

        return $map[$channel] ?? \App\Notifications\DatabaseNotification::class;
    }
}
