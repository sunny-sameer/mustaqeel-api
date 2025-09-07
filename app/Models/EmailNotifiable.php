<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class EmailNotifiable
{
    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    // This method is used by Laravel's notification system to get the email address
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    // This method is used by Laravel's notification system to get the phone number for SMS
    public function routeNotificationForSms($notification)
    {
        return $this->email; // For SMS, this would typically be a phone number
    }

    // Add a simple method to check if this is an email notifiable
    public function isEmailNotifiable()
    {
        return true;
    }
}
