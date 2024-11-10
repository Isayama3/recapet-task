<?php

namespace App\Base\Resources;

use Illuminate\Http\Resources\Json\JsonResource as Resource;

class FrontListResource extends Resource
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
            'label' => __('order.admin.' . $this->resource),
            'value' => $this->resource,
        ];
    }
}
