<?php

use App\Services\NotificationService;
use Illuminate\Container\Container;

if (!function_exists('notify')) {
    /**
     * Get the notification service instance.
     *
     * @return \App\Services\NotificationService
     */
    function notify()
    {
        return Container::getInstance()->make('notify');
    }
} 