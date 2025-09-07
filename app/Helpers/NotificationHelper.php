<?php


use App\Services\V1\Notifications\NotificationBuilder;


if (!function_exists('notify')) {
    function notify()
    {
        return new NotificationBuilder();
    }
}
