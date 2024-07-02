<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Resources\Json\JsonResource;

class MtFiveGroupResource extends JsonResource
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
            'name' => $this->name,
            'leverage' => $this->leverage,
        ];
    }
}
