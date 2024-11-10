<?php

namespace App\Base\Resources;

use Illuminate\Http\Resources\Json\JsonResource as Resource;

class SimpleResourceTow extends Resource
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
            'address' => $this->address,
        ];
    }
}
