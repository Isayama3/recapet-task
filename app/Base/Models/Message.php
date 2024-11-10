<?php

namespace App\Base\Models;

use App\Base\Models\BaseModel;
use App\Models\User;

class Message extends BaseModel
{
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
