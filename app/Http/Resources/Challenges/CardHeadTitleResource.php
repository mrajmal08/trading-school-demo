<?php

namespace App\Http\Resources\Challenges;

use Illuminate\Http\Resources\Json\JsonResource;

class CardHeadTitleResource extends JsonResource
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
