<?php


use App\Services\NotificationBuilder;

if (!function_exists('notify')) {
    function notify()
    {
        return new NotificationBuilder();
    }
}
