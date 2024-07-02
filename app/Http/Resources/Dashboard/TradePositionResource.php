<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class TradePositionResource extends JsonResource
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
            'account_id' => $this->account_id,
            'account' => $this->account,
            'symbol' => $this->symbol,
            'quantity' => $this->quantity,
            'avgPrice' => $this->avgPrice,
            'realizedPl' => $this->realizedPl,
            'openPl' => $this->openPl,
            'totalPl' => $this->totalPl,
            'priceScale' => $this->priceScale,
            // 'challenges_id' => $this->packagePurchaseAccountDetail?->cardChallenge?->title,
        ];
    }
}
