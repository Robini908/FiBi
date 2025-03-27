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

if (!function_exists('settings')) {
    /**
     * Get the settings helper instance.
     *
     * @return \App\Helpers\SettingsHelper
     */
    function settings()
    {
        return Container::getInstance()->make('settings');
    }
}

if (!function_exists('setting')) {
    /**
     * Get a setting by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return App\Models\Setting::get($key, $default);
    }
} 