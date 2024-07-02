<?php

namespace App\Http\Resources\Trading;

use App\Http\Resources\Dashboard\AllTradeResource;
use App\Http\Resources\Dashboard\ChartResource;
use App\Http\Resources\Dashboard\LosingTradeResource;
use App\Http\Resources\Dashboard\ProfitTradeResource;
use App\Http\Resources\Dashboard\TradePositionResource;
use App\Http\Resources\Dashboard\TradeRecordResource;
use App\Http\Resources\Trading\CardCallangeResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RiskManagementListResource extends JsonResource
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
            'user_id' => new UserResource($this->user),
            'amp_id' => $this->amp_id,
            'trader_id' => $this->trader_id,
            'trader_name' => $this->trader_name,
            'account_id' => $this->account_id,
            'account_name' => $this->account_name,
            'card_challenge' => new CardCallangeResource($this->cardChallenge),
            'package_price' => $this->package_price,
            'account_status' => $this->account_status,
            'trading_day' => $this->trading_day,
            'open_contracts' => $this->open_contracts,
            'current_daily_pl' => $this->current_daily_pl,
            'net_liq_value' => $this->net_liq_value,
            'sodbalance' => $this->sodbalance,
            'rule_1_value' => $this->rule_1_value,
            'rule_1_maximum' => $this->rule_1_maximum,
            'rule_2_value' => $this->rule_2_value,
            'rule_2_maximum' => $this->rule_2_maximum,
            'rule_3_value' => $this->rule_3_value,
            'rule_3_maximum' => $this->rule_3_maximum,

            'dashboard_id' => $this->dashboard_id,
            'isActive' => $this->isActive,
            'isPrimary' => $this->isPrimary,
            'accountSize' => $this->accountSize,
            'isLocked' => $this->isLocked,
            'isEmpty' => $this->isEmpty,
            'isMaybeLocked' => $this->isMaybeLocked,
            'balance' => $this->balance,
            'dailyLossLimit' => $this->dailyLossLimit,
            'currentDrawdown' => $this->currentDrawdown,
            'drawdownLimit' => $this->drawdownLimit,
            'rule1Enabled' => $this->rule1Enabled,
            'rule1Key' => $this->rule1Key,
            'rule1Failed' => $this->rule1Failed,
            'rule2Enabled' => $this->rule2Enabled,
            'rule2Key' => $this->rule2Key,
            'rule2Failed' => $this->rule2Failed,
            'rule3Enabled' => $this->rule3Enabled,
            'rule3Key' => $this->rule3Key,
            'rule3Failed' => $this->rule3Failed,
            'profitTarget' => $this->profitTarget,
            'minimumDays' => $this->minimumDays,
            'chartHistory' => ChartResource::collection($this->graphHistory),
            'allTrades' => AllTradeResource::collection($this->allTrade),
            'profitTrades' => ProfitTradeResource::collection($this->profitTrade),
            'losingTrades' => LosingTradeResource::collection($this->losingTrade),
            'tradeHistory' => TradeRecordResource::collection($this->tradeRecord),
            'positions' => TradePositionResource::collection($this->tradePosition),
        ];
    }
}
