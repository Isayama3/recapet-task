<?php

namespace App\Base\Traits\Custom;

use App\Base\Models\Chat;
use App\Base\Models\Message;

trait ChatAttribute
{
    public function sentChats()
    {
        return $this->morphMany(Chat::class, 'sender');
    }

    public function receivedChats()
    {
        return $this->morphMany(Chat::class, 'receiver');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
