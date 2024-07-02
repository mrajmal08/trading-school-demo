<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class TradeRecordResource extends JsonResource
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
            'timestamp' => $this->trade_time,
            'account' => $this->account,
            'symbol' => $this->symbol,
            'buys' => $this->buy,
            'sells' => $this->sale,
            'price' => $this->price,
            'scalePrice' => $this->scalePrice,
            // 'challenges_id' => $this->packagePurchaseAccountDetail?->cardChallenge?->title,
        ];
    }
}
