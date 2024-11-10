<?php

namespace App\Base\Models;

use App\Base\Models\BaseModel;

class Notification extends BaseModel
{
    public function getIconPathUrlAttribute()
    {
        return $this->getImageUrl('icon_path');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function getTargetTypeAttribute()
    {
        $string = $this->notifiable_target_type;
        $result = str_replace("App\Models\\", "", $string);
        return $result;
    }
}
