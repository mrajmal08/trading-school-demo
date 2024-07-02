<?php

namespace App\Http\Resources\Trading;

use App\Http\Resources\Trading\CardCallangeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PackagePurchaseAccountDetailResource extends JsonResource
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
            'card_challenge' => new CardCallangeResource($this->cardChallenge),
            'request_id' => $this->request_id,
            'amp_id' => $this->amp_id,
            'trader_id' => $this->trader_id,
            'trader_name' => $this->trader_name,
            'account_id' => $this->account_id,
            'account_name' => $this->account_name,
            'new_customer_id' => $this->new_customer_id,
            'package_price' => $this->package_price,
            'account_status' => $this->account_status,
            'start_date' => $this->created_at->format('Y-m-d'),
        ];
    }
}
