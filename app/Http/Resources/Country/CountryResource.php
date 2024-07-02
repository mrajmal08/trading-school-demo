<?php

namespace App\Http\Resources\Country;

use App\Http\Resources\Country\StateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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

            'uuid' => $this->uuid,
            'country_name' => $this->country_name,
            'states' => StateResource::collection($this->states),

        ];
    }
}
