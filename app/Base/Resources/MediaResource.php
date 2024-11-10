<?php

namespace App\Base\Resources;

use Illuminate\Http\Resources\Json\JsonResource as Resource;

class MediaResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'extension' => $this->extension,
            'original' => secure_asset($this->original),
            'photo_400' => secure_asset($this->photo_400),
            'photo_600' => secure_asset($this->photo_600),
            'photo_800' => secure_asset($this->photo_800),
            'type' => $this->type,
        ];
    }
}
