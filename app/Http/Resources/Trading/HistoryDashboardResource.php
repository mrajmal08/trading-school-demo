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

class HistoryDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $progressArray = array(
            'account_size' => (int) $this->accountSize, //starting balance
            'balance' => (int) $this->balance, //Account Balance
            'net_liq_value' => (int) $this->netLiqValue, // -$43
            'trading_day' => (int) $this->tradingDay, //Trading day
            'sodbalance' => (int) $this->sodbalance,

            'rule_one_failed' => $this->rule1Failed,
            'rule_one_enable' => $this->rule1Enabled, //40
            'rule_one_value' => (int) $this->rule1Value, //40
            'rule_one_key' => $this->rule1Key, //60
            'rule_one_maximum' => (int) $this->rule1Maximum, //60

            'rule_two_failed' => $this->rule2Failed,

            'rule_two_enable' => $this->rule2Enabled, //40
            'rule_two_value' => (int) $this->rule2Value, //960
            'rule_two_key' => $this->rule2Key, //5000
            'rule_two_maximum' => (int) $this->rule2Maximum, // 5000

            'rule_three_failed' => $this->rule3Failed,

            'rule_three_enable' => $this->rule3Enabled,
            'rule_three_value' => (int) $this->rule3Value, //960
            'rule_three_key' => $this->rule3Key, //5000
            'rule_three_maximum' => (int) $this->rule3Maximum, // 5000

        );
        return [
            'uuid' => $this->uuid,
            'progress' => $progressArray,
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
            'open_contracts' => (int) $this->open_contracts,
            'current_daily_pl' => (int) $this->current_daily_pl,
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
            'dailyLossLimit' => (int) $this->dailyLossLimit,
            'currentDrawdown' => (int) $this->currentDrawdown,
            'drawdownLimit' => (int) $this->drawdownLimit,
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
            'progressChart' => ChartResource::collection($this->graphHistory),
            'allTrades' => AllTradeResource::collection($this->allTrade),
            'profitTrades' => ProfitTradeResource::collection($this->profitTrade),
            'losingTrades' => LosingTradeResource::collection($this->losingTrade),
            'tradeHistory' => TradeRecordResource::collection($this->tradeRecord),
            'positions' => TradePositionResource::collection($this->tradePosition),
        ];
    }
}
