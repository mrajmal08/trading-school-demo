<?php

namespace App\Http\Resources\Challenges;

use App\Http\Resources\Challenges\CardHeadSubTitleResource;
use App\Http\Resources\Challenges\CardHeadTitleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CardHeadResource extends JsonResource
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

            'card_head_title' => new CardHeadTitleResource($this->cardHeadTitle),
            'card_sub_title' => CardHeadSubTitleResource::collection($this->cardHeadSubTitle),

        ];
    }
}
