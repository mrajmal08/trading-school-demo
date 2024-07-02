<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class ChartResource extends JsonResource
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
            'dayIndex' => (string) $this->day_index,
            'eodNetLiq' => (string) $this->eod_net_liq,
            'eodDrawdown' => (string) $this->eod_drawdown,
            'eodProfitTarget' => (string) $this->eod_profit_target,
        ];
    }
}
