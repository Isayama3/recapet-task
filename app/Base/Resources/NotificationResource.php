<?php

namespace App\Base\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'icon' => $this->icon_path,
            'target_id' => $this->notifiable_target_id,
            'target_type' => $this->target_type,
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s')
        ];
    }
}
