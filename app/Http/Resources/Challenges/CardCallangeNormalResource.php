<?php

namespace App\Http\Resources\Challenges;

use App\Http\Resources\Challenges\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CardCallangeNormalResource extends JsonResource
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

            'service' => new ServiceResource($this->service),
            'challenge_uuid' => $this->uuid,
            'title' => $this->title,
            'buying_power' => $this->buying_power,
            'price' => $this->price,

        ];
    }
}
