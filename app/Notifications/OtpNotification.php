<?php

namespace App\Notifications;

class OtpNotification extends DynamicNotification
{
    public function __construct(array $data = [])
    {
        parent::__construct('otp_sent', $data);
    }
}
