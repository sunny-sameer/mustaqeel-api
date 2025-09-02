<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationEvent;
use App\Models\NotificationTemplate;
use App\Models\NotificationChannel;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Create events
        $events = [
            ['name' => 'user_registered', 'description' => 'Triggered when a new user registers'],
            ['name' => 'otp_sent', 'description' => 'Triggered when an OTP is sent to a user'],
        ];

        foreach ($events as $event) {
            NotificationEvent::create($event);
        }

        // Create channels
        $channels = [
            ['name' => 'mail'],
            ['name' => 'sms'],
            ['name' => 'database'],
        ];

        foreach ($channels as $channel) {
            NotificationChannel::create($channel);
        }

        // Create templates
        $templates = [
            [
                'subject' => 'Welcome to our platform, {{name}}!',
                'default_content' => 'Hello {{name}}, welcome to our platform. Your account has been created successfully.',
                'type' => 'mail',
                'placeholders' => json_encode(['name', 'email'])
            ],
            [
                'subject' => 'Your OTP Code',
                'default_content' => 'Hello {{name}}, your OTP is {{otp}} and expires in 5 minutes.',
                'type' => 'mail',
                'placeholders' => json_encode(['name', 'otp'])
            ],
            [
                'subject' => null,
                'default_content' => 'Your OTP is {{otp}} and expires in 5 minutes.',
                'type' => 'sms',
                'placeholders' => json_encode(['otp'])
            ],
            [
                'subject' => null,
                'default_content' => 'New OTP requested for {{name}}',
                'type' => 'database',
                'placeholders' => json_encode(['name', 'otp'])
            ],
        ];

        foreach ($templates as $templateData) {
            $template = NotificationTemplate::create($templateData);

            // Associate with events
            if (strpos($templateData['default_content'], 'welcome') !== false) {
                $template->events()->attach(NotificationEvent::where('name', 'user_registered')->first());
            } else {
                $template->events()->attach(NotificationEvent::where('name', 'otp_sent')->first());
            }
        }
    }
}
