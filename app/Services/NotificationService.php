<?php

namespace App\Services;

use App\Events\NewNotificationEvent;
use App\Notifications\GeneralNotification;

class NotificationService
{
    public static function send($user, $title, $message, $type, $data = [])
    {
        
        $user->notify(new GeneralNotification($title, $message, $type, $data));

        event(new NewNotificationEvent([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ], $user->id));
    }
}