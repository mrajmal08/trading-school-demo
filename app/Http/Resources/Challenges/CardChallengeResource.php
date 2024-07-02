<?php

namespace App\Http\Resources\Challenges;

use App\Http\Resources\Challenges\CardHeadResource;
use App\Http\Resources\Challenges\ChallengeStripResource;
use App\Http\Resources\Challenges\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CardChallengeResource extends JsonResource
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
            'is_feature' => $this->is_feature,
            'stripe_product' => new ChallengeStripResource($this->challengeStripe),
            'card_head' => CardHeadResource::collection($this->cardHead),

        ];
    }
}
