<?php

namespace App\Base\Resources;

use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'type' => auth('user-api')->id() == $this->user_id ? 'sender' : 'receiver',
            'user' => UserResource::make($this->user),
            'created_at'    => Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
