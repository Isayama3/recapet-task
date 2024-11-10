<?php

namespace App\Base\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender' => new UserResource($this->whenLoaded('sender')),
            'receiver' => new UserResource($this->whenLoaded('receiver')),
            'last_message' => MessageResource::make($this->last_message),
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s')
        ];
    }
}
