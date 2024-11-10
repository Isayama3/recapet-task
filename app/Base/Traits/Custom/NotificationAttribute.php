<?php

namespace App\Base\Traits\Custom;

use App\Base\Models\Notification;

trait NotificationAttribute
{
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
