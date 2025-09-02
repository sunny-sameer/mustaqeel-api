<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\NotificationEvent;
use App\Services\MailMergeService;
use App\Models\NotificationTemplate;

class DynamicNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;
    protected $data;
    protected $mergeService;

    public function __construct(string $event, array $data = [])
    {
        $this->event = $event;
        $this->data = $data;
        $this->mergeService = new MailMergeService();
    }

    public function via($notifiable): array
    {
        $event = NotificationEvent::where('name', $this->event)->first();

        if (!$event) {
            return [];
        }

        $channels = [];
        foreach ($event->templates as $template) {
            $channels[] = $template->type;
        }

        return array_unique($channels);
    }

    public function toMail($notifiable): ?MailMessage
    {
        $template = $this->getTemplate('mail');

        if (!$template) {
            return null;
        }

        $content = $template->custom_content ?? $template->default_content;
        $processedContent = $this->mergeService->replacePlaceholders($content, $this->data);
        $processedSubject = $template->subject
            ? $this->mergeService->replacePlaceholders($template->subject, $this->data)
            : 'Notification';


        return (new MailMessage)
            ->subject($processedSubject)
            ->line($processedContent);
    }

    public function toDatabase($notifiable): ?array
    {
        $template = $this->getTemplate('database');

        if (!$template) {
            return null;
        }

        $content = $template->custom_content ?? $template->default_content;
        $processedContent = $this->mergeService->replacePlaceholders($content, $this->data);

        return [
            'message' => $processedContent,
            'event' => $this->event,
            'data' => $this->data,
        ];
    }

    protected function getTemplate(string $type): ?NotificationTemplate
    {
        $event = NotificationEvent::where('name', $this->event)
            ->with(['templates' => function ($query) use ($type) {
                $query->where('type', $type);
            }])
            ->first();

        return $event->templates->first() ?? null;
    }
}
