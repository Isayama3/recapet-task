<?php

namespace App\Base\Models;

use App\Base\Models\BaseModel;

class Chat extends BaseModel
{
    public function getLastMessageAttribute()
    {
        return $this->messages->last();
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->morphTo();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}

