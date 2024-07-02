<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Resources\Json\JsonResource;

class CardCallangeResource extends JsonResource
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
           
            'title' => $this->title,
            
        ];
    }
}
