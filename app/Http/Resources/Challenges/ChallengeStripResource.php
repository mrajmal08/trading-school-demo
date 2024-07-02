<?php

namespace App\Http\Resources\Challenges;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeStripResource extends JsonResource
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
            'stripe_product_id' => $this->stripe_product_id,
            'stripe_product_price_id' => $this->stripe_product_price_id,
        ];
    }
}
