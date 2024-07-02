<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class AllTradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // return [

        //     ['title' => '# of Trades', 'value' => $this->number_trades],
        //     ['title' => '# of Contracts', 'value' => $this->number_contracts],
        //     ['title' => 'Avg Trading Time', 'value' => CarbonInterval::seconds($this->avg_trading_time)->cascade()->forHumans()],
        //     ['title' => 'Longest Trading Time', 'value' => CarbonInterval::seconds($this->longest_trading_time)->cascade()->forHumans()],
        //     ['title' => '% of Profitable Trades', 'value' => $this->percent_profitable . ' %'],
        //     ['title' => 'expectancy', 'value' => $this->expectancy],
        //     ['title' => 'Total P/L', 'value' => "$($this->total_pl)"],
        // ];

        return [

            'uuid' => $this->uuid,
            'account_id_number' => $this->account_id_number,
            'totalPl' => (string) $this->total_pl,
            'avgPlTrade' => (string) $this->avgPlTrade,
            'numberTrades' => (string) $this->number_trades,
            'numberContracts' => (string) $this->number_contracts,
            'avgTradingTime' => $this->avg_trading_time,
            'longestTradingTime' => $this->longest_trading_time,
            'percentProfitable' => (string) $this->percent_profitable,
            'expectancy' => (string) $this->expectancy,
        ];
    }
}
