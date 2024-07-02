<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Resources\Json\JsonResource;

class MarketDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => @$this->uuid,
            'id' => $this->api_id,
            'name' => $this->name,
            'original_price' => $this->original_price,
            'buffer_price' => $this->buffer_price,
            'price' => $this->price,

        ];
    }
}
