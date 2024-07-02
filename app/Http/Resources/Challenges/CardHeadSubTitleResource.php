<?php

namespace App\Http\Resources\Challenges;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Challenges\CardSubHeadTitleResource;

class CardHeadSubTitleResource extends JsonResource
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

            'sub_title' => new CardSubHeadTitleResource($this->cardSubHeadTitle),

        ];
    }
}
