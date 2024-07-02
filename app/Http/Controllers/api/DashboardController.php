<?php

namespace App\Http\Controllers\api;

use App\Events\NewUserRegistration;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\AllTradeResource;
use App\Http\Resources\Dashboard\ChartResource;
use App\Http\Resources\Dashboard\LosingTradeResource;
use App\Http\Resources\Dashboard\ProfitTradeResource;
use App\Http\Resources\Dashboard\TradeAcoountListResource;
// use App\Mail\UserRegister;
// use App\Http\Resources\Challenges\TradeRecordResource;
use App\Http\Resources\Dashboard\TradeRecordResource;
use App\Mail\ChallengeFail;
use App\Models\AllTrade;
use App\Models\GraphHistory;
use App\Models\LosingTrade;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\ProfitTrade;
use App\Models\RiskManagement;
use App\Models\TradePosition;
use App\Models\TradeRecord;
use App\Models\TradingDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use stdClass;

class DashboardController extends Controller
{
    public function tradingDay()
    {
        return response()->json([
            'status' => 200,
            'message' => 'Trading Day',
            'data' => ['card_head' => "Trading Day", 'total_day' => 30, 'remaining_day' => 15, 'remaining_percent' => 50],
        ]);
    }

    public function accountBalance()
    {
        return response()->json([
            'status' => 200,
            'message' => 'Account Balance',
            'data' => ['card_head' => "Account Balance", 'maximum_balance' => 1000000, 'useable_balance' => rand(40, 50), 'calculate_balance' => rand(40, 55)],
        ]);
    }

    public function trading()
    {
        return response()->json([
            'status' => 200,
            'message' => 'Trading Day',
            'data' => ['card_head' => "Trading Day", 'total_trade' => "40/60", 'max_trade' => 60, 'total_loss' => "960/5000", 'loss_max' => 5000, 'suggestion' => "Keep Going"],
        ]);
    }

    public function allTrades()
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {
            $allTrade = AllTrade::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->get();

            if ($allTrade->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => "Error No data found",
                    'data' => "",
                ], 400);
            } else {

                return response()->json([

                    'status' => 200,
                    'message' => "All Trades",
                    'data' => AllTradeResource::collection($allTrade),
                ], 200);
            }
        }

    }
    public function profitTrades()
    {

        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {
            $allTrade = ProfitTrade::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->get();

            if ($allTrade->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => "Error No data found",
                    'data' => "",
                ], 400);
            } else {

                return response()->json([

                    'status' => 200,
                    'message' => "Profit Trades",
                    'data' => ProfitTradeResource::collection($allTrade),
                ], 200);
            }
        }

    }
    public function losingTrades()
    {

        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {
            $allTrade = LosingTrade::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->get();

            if ($allTrade->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => "Error No data found",
                    'data' => "",
                ], 400);
            } else {

                return response()->json([

                    'status' => 200,
                    'message' => "Losing Trades",
                    'data' => LosingTradeResource::collection($allTrade),
                ], 200);
            }
        }

    }

    public function tradeRecord()
    {

        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {
            $allTradeRecord = TradeRecord::where('user_id', $userDetail->id)->paginate(10);

            if ($allTradeRecord->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => "Error No data found",
                    'data' => "",
                ], 400);
            } else {
                $data = TradeRecordResource::collection($allTradeRecord)->resource;
                return response()->json([

                    'status' => 200,
                    'message' => "Trade History",
                    'data' => $data,
                ], 200);
            }
        }

    }

    public function dashboardChart()
    {

        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {
            $allChartRecord = GraphHistory::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->paginate(10);

            if ($allChartRecord->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => "Error No data found",
                    'data' => "",
                ], 400);
            } else {
                $data = ChartResource::collection($allChartRecord)->resource;
                return response()->json([

                    'status' => 200,
                    'message' => "Trading Progress",
                    'data' => $data,
                ], 200);
            }
        }

    }

    public function dashboardChartLastDay()
    {

        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $currentDate = Carbon::yesterday();

            $day = intval($currentDate->format('d'));

            $allChartRecord = GraphHistory::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->where('day_index', $day)->get()->first();

            if (empty($allChartRecord)) {
                return response()->json([
                    'status' => 400,
                    'message' => "Error No data found",
                ], 400);
            } else {
                $data = new ChartResource($allChartRecord);
                return response()->json([
                    'status' => 200,
                    'message' => "Trading Progress",
                    'data' => $data,
                ], 200);
            }
        }

    }

    public function dashboardChartLastWeek()
    {

        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $startWeek = Carbon::now()->subWeek()->startOfWeek();
            $endWeek = Carbon::now()->subWeek()->endOfWeek();

            $startday = intval($startWeek->format('d'));
            $endday = intval($endWeek->format('d'));

            $allChartRecord = GraphHistory::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->whereBetween('day_index', [$startday, $endday])->get();

            if (empty($allChartRecord)) {
                return response()->json([
                    'status' => 400,
                    'message' => "Error No data found",
                ], 400);
            } else {
                $data = ChartResource::collection($allChartRecord);
                return response()->json([
                    'status' => 200,
                    'message' => "Trading Progress",
                    'data' => $data,
                ], 200);
            }
        }

    }

    public function userTradeAccountList()
    {

        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $allTradeRecord = TradeRecord::with('packagePurchaseAccountDetail')->where('user_id', $userDetail->id)->get();

            $data = TradeAcoountListResource::collection($allTradeRecord);
            return response()->json([

                'status' => 200,
                'message' => "Account List With Challenges",
                'data' => $data,
            ], 200);

            // if ($allTradeRecord->isEmpty()) {
            //     return response()->json([
            //         'status' => 400,
            //         'message' => "Error No data found",
            //         'data' => "",
            //     ], 400);
            // } else {
            //     $data = TradeAcoountListResource::collection($allTradeRecord);
            //     return response()->json([

            //         'status' => 200,
            //         'message' => "Account List With Challenges",
            //         'data' => $data,
            //     ], 200);
            // }
        }

    }

    public function upDateDashBoard()
    {

        $userDetail = Auth::guard('api')->user();

        if ((empty($userDetail->account_id_number)) || (empty($userDetail->ampid)) || (empty($userDetail->traderid)) || (empty($userDetail->account_name))) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $dashBoardUrl = config('app.go_lang_url');

            $accountID = $userDetail->account_id_number;
            $uuid = $userDetail->uuid;
            $tradeID = $userDetail->traderid;
            $ampID = $userDetail->ampid;
            $userId = $userDetail->id;
            $acountName = $userDetail->account_name;

            $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $accountID . "/" . $tradeID . "/" . $acountName;

            $response = Http::get($dashBoardUrl);

            if (($response->successful() == false) || ($response->failed() == true)) {
                return response()->json([
                    'status' => 400,
                    'message' => "Go Lang Server Error",
                ], 400);
            }
            if (($response->successful() == true) && ($response->failed() == false) && (!empty($response->collect()))) {

                $goLangDbValue = $response->collect();
                $goLangDbValue = $response->object();

                $compareArray = array(
                    'user_id' => $userId,
                    'account_id_number' => $accountID,
                    'uuid' => $uuid,
                );

                $dataArray = array(

                    'api_id' => $goLangDbValue->id,
                    'is_active' => $goLangDbValue->isActive,
                    'is_primary' => $goLangDbValue->isPrimary,
                    'account_size' => $goLangDbValue->accountSize,
                    'account_name' => $goLangDbValue->accountName,
                    'is_locked' => $goLangDbValue->isLocked,
                    'is_empty' => $goLangDbValue->isEmpty,
                    'is_maybe_locked' => $goLangDbValue->isMaybeLocked,
                    'balance' => $goLangDbValue->balance,
                    'sodbalance' => $goLangDbValue->sodbalance,
                    'current_daily_pl' => $goLangDbValue->currentDailyPl,
                    'open_contracts' => $goLangDbValue->openContracts,
                    'daily_loss_limit' => $goLangDbValue->dailyLossLimit,
                    'net_liq_value' => $goLangDbValue->netLiqValue,
                    'current_drawdown' => $goLangDbValue->currentDrawdown,
                    'drawdown_limit' => $goLangDbValue->drawdownLimit,
                    'drawdown_type' => $goLangDbValue->drawdownType,
                    'trading_day' => (int) $goLangDbValue->tradingDay,
                    'rule_one_enabled' => $goLangDbValue->rule1Enabled,
                    'rule_one_value' => $goLangDbValue->rule1Value,
                    'rule_one_key' => $goLangDbValue->rule1Key,
                    'rule_one_maximum' => $goLangDbValue->rule1Maximum,
                    'rule_two_enabled' => $goLangDbValue->rule2Enabled,
                    'rule_two_value' => $goLangDbValue->rule2Value,
                    'rule_two_key' => $goLangDbValue->rule2Key,
                    'rule_two_maximum' => $goLangDbValue->rule2Maximum,
                    'rule_three_enabled' => $goLangDbValue->rule3Enabled,
                    'rule_three_value' => $goLangDbValue->rule3Value,
                    'rule_three_key' => $goLangDbValue->rule3Key,
                    'rule_three_maximum' => $goLangDbValue->rule3Maximum,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                );

                $dashBoardData = DB::transaction(function () use ($userId, $accountID, $compareArray, $dataArray, $goLangDbValue) {

                    $traDingDetail = TradingDetail::updateOrCreate(
                        $compareArray,
                        $dataArray
                    );

                    $tradeCompareArray = array(
                        'user_id' => $userId,
                        'account_id_number' => $accountID,
                    );

                    $allTradeDataArray = array(
                        'uuid' => (string) Str::uuid(),
                        'total_pl' => $goLangDbValue?->allTrades?->totalPl,
                        'number_trades' => $goLangDbValue?->allTrades?->numberTrades,
                        'number_contracts' => $goLangDbValue?->allTrades?->numberContracts,
                        'avg_trading_time' => $goLangDbValue?->allTrades?->avgTradingTime,
                        'longest_trading_time' => $goLangDbValue?->allTrades?->longestTradingTime,
                        'percent_profitable' => $goLangDbValue?->allTrades?->percentProfitable,
                        'expectancy' => $goLangDbValue?->allTrades?->expectancy,
                    );

                    $profitTradeDataArray = array(
                        'uuid' => (string) Str::uuid(),
                        'total_pl' => $goLangDbValue?->profitTrades?->totalPl,
                        'number_trades' => $goLangDbValue?->profitTrades?->numberTrades,
                        'number_contracts' => $goLangDbValue?->profitTrades?->numberContracts,
                        'avg_trading_time' => $goLangDbValue?->profitTrades?->avgTradingTime,
                        'longest_trading_time' => $goLangDbValue?->profitTrades?->longestTradingTime,
                        'percent_profitable' => $goLangDbValue?->profitTrades?->percentProfitable,
                        'expectancy' => $goLangDbValue?->profitTrades?->expectancy,
                    );

                    $loseTradeDataArray = array(
                        'uuid' => (string) Str::uuid(),
                        'total_pl' => $goLangDbValue?->losingTrades?->totalPl,
                        'number_trades' => $goLangDbValue?->losingTrades?->numberTrades,
                        'number_contracts' => $goLangDbValue?->losingTrades?->numberContracts,
                        'avg_trading_time' => $goLangDbValue?->losingTrades?->avgTradingTime,
                        'longest_trading_time' => $goLangDbValue?->losingTrades?->longestTradingTime,
                        'percent_profitable' => $goLangDbValue?->losingTrades?->percentProfitable,
                        'expectancy' => $goLangDbValue?->losingTrades?->expectancy,
                    );

                    $allTradeDetail = AllTrade::updateOrCreate(
                        $tradeCompareArray,
                        $allTradeDataArray
                    );
                    ProfitTrade::updateOrCreate(
                        $tradeCompareArray,
                        $profitTradeDataArray
                    );
                    LosingTrade::updateOrCreate(
                        $tradeCompareArray,
                        $loseTradeDataArray
                    );

                    $goLangChartDatas = $goLangDbValue?->chartHistory;

                    $chartCompareArray = array(
                        'user_id' => $userId,
                        'account_id_number' => $accountID,
                    );

                    foreach ($goLangChartDatas as $key => $chartData) {
                        $chartCompareArray['day_index'] = $chartData->dayIndex;

                        $chartDataArray = array(
                            'uuid' => (string) Str::uuid(),
                            'eod_net_liq' => $chartData->eodNetLiq,
                            'eod_drawdown' => $chartData->eodDrawdown,
                            'eod_profit_target' => $chartData->eodProfitTarget,
                        );

                        GraphHistory::updateOrCreate(
                            $chartCompareArray,
                            $chartDataArray
                        );

                    }

                    $goLangTradeHistory = $goLangDbValue?->tradeHistory;

                    $tradeRecordCompareArray = array(
                        'user_id' => $userId,
                        'account' => $accountID,
                    );

                    foreach ($goLangTradeHistory as $key => $tradeHistoryData) {

                        $timestamformate = Carbon::createFromTimestamp($tradeHistoryData->timestamp)->toDateTimeString();

                        // $tradeRecordCompareArray['trade_time'] = $tradeHistoryData->timestamp;
                        $tradeRecordCompareArray['trade_time'] = $timestamformate;

                        $tradeRecordDataArray = array(
                            'uuid' => (string) Str::uuid(),
                            'symbol' => $tradeHistoryData->symbol,
                            'buy' => $tradeHistoryData->buys,
                            'sale' => $tradeHistoryData->sells,
                            // 'quantity' => $tradeHistoryData->quantity,
                            'price' => $tradeHistoryData->price,
                        );

                        TradeRecord::updateOrCreate(
                            $tradeRecordCompareArray,
                            $tradeRecordDataArray
                        );

                    }

                    return $goLangDbValue;

                });

                return response()->json([
                    'status' => 200,
                    'message' => "Dash board Data Update",
                    'data' => $response->collect(),
                ], 200);
            }

            return response()->json([
                'status' => 400,
                'message' => "Data Not Updated",
            ], 400);

        }

    }

    public function progressDaterange(Request $request)
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // $allTrade = AllTrade::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->whereBetween('created_at', [$startDate, $endDate])->get();
            // $profitTrade = ProfitTrade::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->whereBetween('created_at', [$startDate, $endDate])->get();
            // $loseTrade = LosingTrade::where('user_id', $userDetail->id)->where('account_id_number', $userDetail->account_id_number)->whereBetween('created_at', [$startDate, $endDate])->get();

            $tradeRecord = TradeRecord::where('user_id', $userDetail->id)->where('account', $userDetail->account_id_number)->whereBetween('created_at', [$startDate, $endDate])->paginate(10);

            if ($tradeRecord->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => "Error No data found",
                    'data' => "",
                ], 400);
            } else {
                $data = $data = TradeRecordResource::collection($tradeRecord)->resource;
                return response()->json([

                    'status' => 200,
                    'message' => "Trade Record",
                    'data' => $data,
                ], 200);
            }
        }

    }

    public function newDashboardData(Request $request)
    {
        $userDetail = Auth::guard('api')->user();

        if ((empty($userDetail->account_id_number)) || (empty($userDetail->ampid)) || (empty($userDetail->traderid)) || (empty($userDetail->account_name))) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {
            if ($userDetail?->userDetail?->status == 0) {
                return response()->json([
                    'status' => 400,
                    'message' => "User is not a active subscriber",
                    'data' => "",
                ], 400);
            }

            $dashBoardUrl = config('app.go_lang_url');

            $accountID = $userDetail->account_id_number;
            $uuid = $userDetail->uuid;
            $tradeID = $userDetail->traderid;
            $ampID = $userDetail->ampid;
            $userId = $userDetail->id;
            $acountName = $userDetail->account_name;

            if ((!empty($request->startData)) && (!empty($request->endData))) {
                $date = Carbon::parse($request->startData);
                $now = Carbon::now();
                $diff = $date->diffInDays($now);
                if ($diff > 30) {
                    return response()->json([
                        'status' => 400,
                        'message' => "Invalid Start date. Can not more then previous 30 days from today ",
                        'data' => "",
                    ], 400);
                }
                $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $accountID . "/" . $tradeID . "/" . $acountName . "?startDate=" . $request->startData . "&endDate=" . $request->endData;

            } else {

                $startDate = $userDetail?->userDetail?->start_date;
                $newStartDate = Carbon::parse($startDate);
                $endData = date("Y-m-d");

                $newEndData = Carbon::parse($endData);
                $result = $newStartDate->eq($newEndData);

                $endData = $newEndData->addDay(1);

                if ($result) {
                    $startDate = Carbon::parse($startDate)->subDay(1);
                }

                $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $accountID . "/" . $tradeID . "/" . $acountName . "?startDate=" . $startDate . "&endDate=" . $endData;

            }
            // $dashBoardUrl = "https://trading-backend.dev.spacecats.tech/core/api/v1/futures/dashboard/history/2031815/G1151535/amt749520?startDate=2023-03-10&endDate=2023-03-25";

            $response = Http::get($dashBoardUrl);

            $goLangDbValue = $response->object();

            if (empty($goLangDbValue)) {
                return response()->json([
                    'status' => 400,
                    'message' => "No Data found",
                ], 400);
            }
            if ($goLangDbValue->status == "fail") {
                return response()->json([
                    'status' => 503,
                    'message' => "Account processing",
                ], 200);
            }

            if (($response->successful() == false) || ($response->failed() == true)) {
                return response()->json([
                    'status' => 400,
                    'message' => "Go Lang Server Error",
                ], 400);
            }

            if (($response->successful() == true) && ($response->failed() == false) && (!empty($goLangDbValue))) {

                $status = $goLangDbValue->status;
                if ($status == "fail") {
                    return response()->json([
                        'status' => 400,
                        'message' => "Go Lang Api Response Fail",
                    ], 400);
                }

                $goLangDbValue = $goLangDbValue->data;

                $progressArray = array(
                    'account_size' => $goLangDbValue->accountSize, //starting balance
                    'balance' => $goLangDbValue->balance, //Account Balance
                    'net_liq_value' => $goLangDbValue->netLiqValue, // -$43
                    'trading_day' => (int) $goLangDbValue->tradingDay, //Trading day
                    'sodbalance' => $goLangDbValue->sodbalance,

                    'rule_one_failed' => $goLangDbValue->rule1Failed,
                    'rule_one_enable' => $goLangDbValue->rule1Enabled, //40
                    'rule_one_value' => $goLangDbValue->rule1Value, //40
                    'rule_one_key' => $goLangDbValue->rule1Key, //60
                    'rule_one_maximum' => $goLangDbValue->rule1Maximum, //60

                    'rule_two_failed' => $goLangDbValue->rule2Failed,

                    'rule_two_enable' => $goLangDbValue->rule2Enabled, //40
                    'rule_two_value' => $goLangDbValue->rule2Value, //960
                    'rule_two_key' => $goLangDbValue->rule2Key, //5000
                    'rule_two_maximum' => $goLangDbValue->rule2Maximum, // 5000

                    'rule_three_failed' => $goLangDbValue->rule3Failed,

                    'rule_three_enable' => $goLangDbValue->rule3Enabled,
                    'rule_three_value' => $goLangDbValue->rule3Value, //960
                    'rule_three_key' => $goLangDbValue->rule3Key, //5000
                    'rule_three_maximum' => $goLangDbValue->rule3Maximum, // 5000

                );

                $goLangChartDatas = $goLangDbValue?->chartHistory;
                $goLangTradeHistory = $goLangDbValue?->tradeHistory;
                $goLangAllTrades = $goLangDbValue?->allTrades;
                $goLangProfitTrades = $goLangDbValue?->profitTrades;
                $goLangLosingTrades = $goLangDbValue?->losingTrades;
                $goLangPositions = $goLangDbValue?->positions;
                $goLangProfitTarget = $goLangDbValue?->profitTarget;
                $goLangMinimumDays = $goLangDbValue?->minimumDays;
                $goLangIsActive = $goLangDbValue?->isActive;

                //check for status success/fail if fail change the user detail from 1 to 0;

                //

                $getPurchaseData = PackagePurchaseAccountDetail::where('account_id', $userDetail?->account_id_number)->first();

                if (($goLangDbValue?->isActive == "False") && ($getPurchaseData?->account_status != "Fail") && (!empty($getPurchaseData))) {
                    $getPurchaseData->challenge_fail_popup = true;
                    $getPurchaseData->account_status = "Fail";
                    $getPurchaseData->save();

                    $mailObject = new stdClass;
                    $mailObject->name = $userDetail?->userDetail?->first_name . ' ' . $userDetail?->userDetail?->last_name;

                    try {
                        Mail::to($userDetail?->email)->send(new ChallengeFail($mailObject));
                    } catch (Exception $e) {
                        $e->getMessage();
                    }

                    $notification = $this->notification($userDetail);

                } elseif (($goLangDbValue?->isActive != "False")) {
                    $getPurchaseData->challenge_fail_popup = false;
                    $getPurchaseData->account_status = "In-Progress";
                    $getPurchaseData->save();
                }

                PackagePurchaseAccountDetail::where('account_id', $userDetail?->account_id_number)->update(["challenge_fail_popup" => false]);

                //New Code For Modification
                (double) $profitTarget = $goLangDbValue?->profitTarget;
                (int) $miniMumDay = $goLangDbValue?->minimumDays;
                (int) $tradingDay = $goLangDbValue?->tradingDay;
                (double) $currentBalance = (double) $goLangDbValue?->netLiqValue - (double) $goLangDbValue?->sodbalance;

                if (($tradingDay > $miniMumDay) && ($currentBalance >= $profitTarget)) {
                    PackagePurchaseAccountDetail::where('account_id', $userDetail?->account_id_number)->update(["account_status" => "Success - Pending Verification"]);
                }

                if (($tradingDay > 30) && ($currentBalance < $profitTarget)) {
                    PackagePurchaseAccountDetail::where('account_id', $userDetail?->account_id_number)->update(["account_status" => "Fail"]);

                    $mailObject = new stdClass;
                    $mailObject->name = $userDetail?->userDetail?->first_name . ' ' . $userDetail?->userDetail?->last_name;

                    try {
                        Mail::to($userDetail?->email)->send(new ChallengeFail($mailObject));
                    } catch (Exception $e) {
                        $e->getMessage();
                    }

                    $notification = $this->notification($userDetail);
                }

                //New Code for risk_management
                $dbInsert = $this->storeDashboardData($goLangDbValue, $userDetail);
                // DB::transaction(function () use ($goLangDbValue, $userDetail) {
                //     $packagePurchase = PackagePurchaseAccountDetail::orderBy('id', 'DESC')->where('account_id', $userDetail?->account_id_number)->first();

                //     $riskUserUnique['user_id'] = $packagePurchase?->user_id;
                //     $riskUserUnique['account_id'] = $packagePurchase?->account_id;

                //     $riskManagement['amp_id'] = $packagePurchase?->amp_id;
                //     $riskManagement['trader_id'] = $packagePurchase?->trader_id;
                //     $riskManagement['trader_name'] = $packagePurchase?->trader_name;
                //     // $riskManagement['account_id'] = $packagePurchase?->account_id;
                //     $riskManagement['account_name'] = $packagePurchase?->account_name;
                //     $riskManagement['card_challenge_id'] = $packagePurchase?->card_challenge_id;
                //     $riskManagement['package_price'] = $packagePurchase?->package_price;
                //     $riskManagement['account_status'] = $packagePurchase?->account_status;

                //     $riskManagement['open_contracts'] = $goLangDbValue->openContracts;
                //     $riskManagement['current_daily_pl'] = $goLangDbValue->currentDailyPl;
                //     $riskManagement['trading_day'] = (int) $goLangDbValue->tradingDay;
                //     $riskManagement['net_liq_value'] = $goLangDbValue->netLiqValue;
                //     $riskManagement['sodbalance'] = $goLangDbValue->sodbalance;
                //     $riskManagement['rule_1_value'] = $goLangDbValue->rule1Value;
                //     $riskManagement['rule_1_maximum'] = $goLangDbValue->rule1Maximum;
                //     $riskManagement['rule_2_value'] = $goLangDbValue->rule2Value;
                //     $riskManagement['rule_2_maximum'] = $goLangDbValue->rule2Maximum;
                //     $riskManagement['rule_3_value'] = $goLangDbValue->rule3Value;
                //     $riskManagement['rule_3_maximum'] = $goLangDbValue->rule3Maximum;

                //     $riskManagement['dashboard_id'] = $goLangDbValue->id;
                //     $riskManagement['isActive'] = $goLangDbValue->isActive;
                //     $riskManagement['isPrimary'] = $goLangDbValue->isPrimary;
                //     $riskManagement['accountSize'] = $goLangDbValue->accountSize;
                //     $riskManagement['isLocked'] = $goLangDbValue->isLocked;
                //     $riskManagement['isEmpty'] = $goLangDbValue->isEmpty;
                //     $riskManagement['isMaybeLocked'] = $goLangDbValue->isMaybeLocked;
                //     $riskManagement['balance'] = $goLangDbValue->balance;
                //     $riskManagement['dailyLossLimit'] = $goLangDbValue->dailyLossLimit;
                //     $riskManagement['currentDrawdown'] = $goLangDbValue->currentDrawdown;
                //     $riskManagement['drawdownLimit'] = $goLangDbValue->drawdownLimit;
                //     $riskManagement['rule1Enabled'] = $goLangDbValue->rule1Enabled;
                //     $riskManagement['rule1Key'] = $goLangDbValue->rule1Key;
                //     $riskManagement['rule1Failed'] = $goLangDbValue->rule1Failed;
                //     $riskManagement['rule2Enabled'] = $goLangDbValue->rule2Enabled;
                //     $riskManagement['rule2Key'] = $goLangDbValue->rule2Key;
                //     $riskManagement['rule2Failed'] = $goLangDbValue->rule2Failed;
                //     $riskManagement['rule3Enabled'] = $goLangDbValue->rule3Enabled;
                //     $riskManagement['rule3Key'] = $goLangDbValue->rule3Key;
                //     $riskManagement['rule3Failed'] = $goLangDbValue->rule3Failed;
                //     $riskManagement['profitTarget'] = $goLangDbValue->profitTarget;
                //     $riskManagement['minimumDays'] = $goLangDbValue->minimumDays;

                //     $risk_management = RiskManagement::updateOrCreate(
                //         $riskUserUnique,
                //         $riskManagement
                //     );

                //     $tradeCompareArray = array(
                //         'user_id' => $packagePurchase?->user_id,
                //         'account_id_number' => $packagePurchase?->account_id,
                //         'risk_management_id' => $risk_management?->id,

                //     );

                //     $allTradeDataArray = array(
                //         'uuid' => (string) Str::uuid(),
                //         'total_pl' => $goLangDbValue?->allTrades?->totalPl,
                //         'avgPlTrade' => $goLangDbValue?->allTrades?->avgPlTrade,
                //         'number_trades' => $goLangDbValue?->allTrades?->numberTrades,
                //         'number_contracts' => $goLangDbValue?->allTrades?->numberContracts,
                //         'avg_trading_time' => $goLangDbValue?->allTrades?->avgTradingTime,
                //         'longest_trading_time' => $goLangDbValue?->allTrades?->longestTradingTime,
                //         'percent_profitable' => $goLangDbValue?->allTrades?->percentProfitable,
                //         'expectancy' => $goLangDbValue?->allTrades?->expectancy,
                //     );

                //     $profitTradeDataArray = array(
                //         'uuid' => (string) Str::uuid(),
                //         'total_pl' => $goLangDbValue?->profitTrades?->totalPl,
                //         'avgPlTrade' => $goLangDbValue?->profitTrades?->avgPlTrade,
                //         'number_trades' => $goLangDbValue?->profitTrades?->numberTrades,
                //         'number_contracts' => $goLangDbValue?->profitTrades?->numberContracts,
                //         'avg_trading_time' => $goLangDbValue?->profitTrades?->avgTradingTime,
                //         'longest_trading_time' => $goLangDbValue?->profitTrades?->longestTradingTime,
                //         'percent_profitable' => $goLangDbValue?->profitTrades?->percentProfitable,
                //         'expectancy' => $goLangDbValue?->profitTrades?->expectancy,
                //     );

                //     $loseTradeDataArray = array(
                //         'uuid' => (string) Str::uuid(),
                //         'total_pl' => $goLangDbValue?->losingTrades?->totalPl,
                //         'avgPlTrade' => $goLangDbValue?->losingTrades?->avgPlTrade,
                //         'number_trades' => $goLangDbValue?->losingTrades?->numberTrades,
                //         'number_contracts' => $goLangDbValue?->losingTrades?->numberContracts,
                //         'avg_trading_time' => $goLangDbValue?->losingTrades?->avgTradingTime,
                //         'longest_trading_time' => $goLangDbValue?->losingTrades?->longestTradingTime,
                //         'percent_profitable' => $goLangDbValue?->losingTrades?->percentProfitable,
                //         'expectancy' => $goLangDbValue?->losingTrades?->expectancy,
                //     );

                //     AllTrade::updateOrCreate(
                //         $tradeCompareArray,
                //         $allTradeDataArray
                //     );

                //     ProfitTrade::updateOrCreate(
                //         $tradeCompareArray,
                //         $profitTradeDataArray
                //     );
                //     LosingTrade::updateOrCreate(
                //         $tradeCompareArray,
                //         $loseTradeDataArray
                //     );

                //     $goLangChartDatas = $goLangDbValue?->chartHistory;

                //     $chartCompareArray = array(
                //         'user_id' => $packagePurchase?->user_id,
                //         'account_id_number' => $packagePurchase?->account_id,
                //         'risk_management_id' => $risk_management?->id,
                //     );

                //     foreach ($goLangChartDatas as $key => $chartData) {
                //         $chartCompareArray['day_index'] = $chartData->dayIndex;

                //         $chartDataArray = array(
                //             'uuid' => (string) Str::uuid(),
                //             'eod_net_liq' => $chartData->eodNetLiq,
                //             'eod_drawdown' => $chartData->eodDrawdown,
                //             'eod_profit_target' => $chartData->eodProfitTarget,
                //         );

                //         GraphHistory::updateOrCreate(
                //             $chartCompareArray,
                //             $chartDataArray
                //         );

                //     }

                //     $goLangTradeHistory = $goLangDbValue?->tradeHistory;

                //     $tradeRecordCompareArray = array(
                //         'user_id' => $packagePurchase?->user_id,
                //         'account' => $packagePurchase?->account_id,
                //         'risk_management_id' => $risk_management?->id,
                //     );

                //     foreach ($goLangTradeHistory as $key => $tradeHistoryData) {
                //         $timestamformate = $tradeHistoryData->timestamp;
                //         $tradeRecordCompareArray['trade_time'] = $timestamformate;

                //         $tradeRecordDataArray = array(
                //             'uuid' => (string) Str::uuid(),
                //             'symbol' => $tradeHistoryData?->symbol,
                //             'buy' => $tradeHistoryData?->buys,
                //             'sale' => $tradeHistoryData?->sells,
                //             'price' => $tradeHistoryData?->price,
                //             'scalePrice' => $tradeHistoryData?->scalePrice,
                //         );

                //         TradeRecord::updateOrCreate(
                //             $tradeRecordCompareArray,
                //             $tradeRecordDataArray
                //         );

                //     }

                //     $goLangTradePosition = $goLangDbValue?->positions;

                //     $tradePositionCompareArray = array(
                //         'user_id' => $packagePurchase?->user_id,
                //         'account_id' => $packagePurchase?->account_id,
                //         'risk_management_id' => $risk_management?->id,
                //     );

                //     foreach ($goLangTradePosition as $pkey => $tradePositionData) {
                //         $tradePositionCompareArray['index'] = (int) $pkey + 1;

                //         $tradePositionDataArray = array(
                //             'uuid' => (string) Str::uuid(),
                //             'symbol' => $tradePositionData?->symbol,
                //             'quantity' => $tradePositionData?->quantity,
                //             'avgPrice' => $tradePositionData?->avgPrice,
                //             'realizedPl' => $tradePositionData?->realizedPl,
                //             'openPl' => $tradePositionData?->openPl,
                //             'totalPl' => $tradePositionData?->totalPl,
                //             'priceScale' => $tradePositionData?->priceScale,
                //         );

                //         TradePosition::updateOrCreate(
                //             $tradePositionCompareArray,
                //             $tradePositionDataArray
                //         );
                //     }

                // });

                //New Code for risk_management

                //New Code For Modification

                return response()->json([
                    'status' => 200,
                    'message' => "Dashboard Data",
                    'data' => [
                        "progress" => $progressArray,
                        "progressChart" => $goLangChartDatas,
                        "tradeHistory" => $goLangTradeHistory,
                        "allTrades" => $goLangAllTrades,
                        "profitTrades" => $goLangProfitTrades,
                        "losingTrades" => $goLangLosingTrades,
                        "positions" => $goLangPositions,
                        "profitTarget" => $goLangProfitTarget,
                        "minimumDays" => $goLangMinimumDays,
                        "challenge_fail_popup" => $getPurchaseData?->challenge_fail_popup,
                        "rule_fail_popup" => $getPurchaseData?->rule_fail_popup,
                        "is_active" => $goLangIsActive,
                    ],
                ], 200);

            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "No Data found",
                ], 400);
            }
        }
    }

    public function notification($user)
    {

        $data['image'] = "";
        $data['head_title'] = "challenge plan has failed";
        $data['message'] = "Your challenge plan has failed due to exceeding daily limits. Tap to see details";
        $data['date'] = date('Y-m-d');
        $data['users'] = $user->id;

        $result = event(new NewUserRegistration($data));

        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = array($user->device_token);

        $serverKey = config('app.fire_base_key');
        $message = array(
            $data['message'],
            $data['image'],
            $data['date'],

        );
        $pushdata = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $data['head_title'],
                "body" => $message,
                "icon" => $data['image'],

            ],
        ];
        $encodedData = json_encode($pushdata);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $nresult = curl_exec($ch);
        if ($nresult === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function storeDashboardData($goLangDbValue, $userDetail)
    {
        $status = DB::transaction(function () use ($goLangDbValue, $userDetail) {
            $packagePurchase = PackagePurchaseAccountDetail::orderBy('id', 'DESC')->where('account_id', $userDetail?->account_id_number)->first();

            $riskUserUnique['user_id'] = $packagePurchase?->user_id;
            $riskUserUnique['account_id'] = $packagePurchase?->account_id;

            $riskManagement['amp_id'] = $packagePurchase?->amp_id;
            $riskManagement['trader_id'] = $packagePurchase?->trader_id;
            $riskManagement['trader_name'] = $packagePurchase?->trader_name;
            // $riskManagement['account_id'] = $packagePurchase?->account_id;
            $riskManagement['account_name'] = $packagePurchase?->account_name;
            $riskManagement['card_challenge_id'] = $packagePurchase?->card_challenge_id;
            $riskManagement['package_price'] = $packagePurchase?->package_price;
            $riskManagement['account_status'] = $packagePurchase?->account_status;

            $riskManagement['open_contracts'] = $goLangDbValue->openContracts;
            $riskManagement['current_daily_pl'] = $goLangDbValue->currentDailyPl;
            $riskManagement['trading_day'] = (int) $goLangDbValue->tradingDay;
            $riskManagement['net_liq_value'] = $goLangDbValue->netLiqValue;
            $riskManagement['sodbalance'] = $goLangDbValue->sodbalance;
            $riskManagement['rule_1_value'] = $goLangDbValue->rule1Value;
            $riskManagement['rule_1_maximum'] = $goLangDbValue->rule1Maximum;
            $riskManagement['rule_2_value'] = $goLangDbValue->rule2Value;
            $riskManagement['rule_2_maximum'] = $goLangDbValue->rule2Maximum;
            $riskManagement['rule_3_value'] = $goLangDbValue->rule3Value;
            $riskManagement['rule_3_maximum'] = $goLangDbValue->rule3Maximum;

            $riskManagement['dashboard_id'] = $goLangDbValue->id;
            $riskManagement['isActive'] = $goLangDbValue->isActive;
            $riskManagement['isPrimary'] = $goLangDbValue->isPrimary;
            $riskManagement['accountSize'] = $goLangDbValue->accountSize;
            $riskManagement['isLocked'] = $goLangDbValue->isLocked;
            $riskManagement['isEmpty'] = $goLangDbValue->isEmpty;
            $riskManagement['isMaybeLocked'] = $goLangDbValue->isMaybeLocked;
            $riskManagement['balance'] = $goLangDbValue->balance;
            $riskManagement['dailyLossLimit'] = $goLangDbValue->dailyLossLimit;
            $riskManagement['currentDrawdown'] = $goLangDbValue->currentDrawdown;
            $riskManagement['drawdownLimit'] = $goLangDbValue->drawdownLimit;
            $riskManagement['rule1Enabled'] = $goLangDbValue->rule1Enabled;
            $riskManagement['rule1Key'] = $goLangDbValue->rule1Key;
            $riskManagement['rule1Failed'] = $goLangDbValue->rule1Failed;
            $riskManagement['rule2Enabled'] = $goLangDbValue->rule2Enabled;
            $riskManagement['rule2Key'] = $goLangDbValue->rule2Key;
            $riskManagement['rule2Failed'] = $goLangDbValue->rule2Failed;
            $riskManagement['rule3Enabled'] = $goLangDbValue->rule3Enabled;
            $riskManagement['rule3Key'] = $goLangDbValue->rule3Key;
            $riskManagement['rule3Failed'] = $goLangDbValue->rule3Failed;
            $riskManagement['profitTarget'] = $goLangDbValue->profitTarget;
            $riskManagement['minimumDays'] = $goLangDbValue->minimumDays;

            $risk_management = RiskManagement::updateOrCreate(
                $riskUserUnique,
                $riskManagement
            );

            $tradeCompareArray = array(
                'user_id' => $packagePurchase?->user_id,
                'account_id_number' => $packagePurchase?->account_id,
                'risk_management_id' => $risk_management?->id,

            );

            $allTradeDataArray = array(
                'uuid' => (string) Str::uuid(),
                'total_pl' => $goLangDbValue?->allTrades?->totalPl,
                'avgPlTrade' => $goLangDbValue?->allTrades?->avgPlTrade,
                'number_trades' => $goLangDbValue?->allTrades?->numberTrades,
                'number_contracts' => $goLangDbValue?->allTrades?->numberContracts,
                'avg_trading_time' => $goLangDbValue?->allTrades?->avgTradingTime,
                'longest_trading_time' => $goLangDbValue?->allTrades?->longestTradingTime,
                'percent_profitable' => $goLangDbValue?->allTrades?->percentProfitable,
                'expectancy' => $goLangDbValue?->allTrades?->expectancy,
            );

            $profitTradeDataArray = array(
                'uuid' => (string) Str::uuid(),
                'total_pl' => $goLangDbValue?->profitTrades?->totalPl,
                'avgPlTrade' => $goLangDbValue?->profitTrades?->avgPlTrade,
                'number_trades' => $goLangDbValue?->profitTrades?->numberTrades,
                'number_contracts' => $goLangDbValue?->profitTrades?->numberContracts,
                'avg_trading_time' => $goLangDbValue?->profitTrades?->avgTradingTime,
                'longest_trading_time' => $goLangDbValue?->profitTrades?->longestTradingTime,
                'percent_profitable' => $goLangDbValue?->profitTrades?->percentProfitable,
                'expectancy' => $goLangDbValue?->profitTrades?->expectancy,
            );

            $loseTradeDataArray = array(
                'uuid' => (string) Str::uuid(),
                'total_pl' => $goLangDbValue?->losingTrades?->totalPl,
                'avgPlTrade' => $goLangDbValue?->losingTrades?->avgPlTrade,
                'number_trades' => $goLangDbValue?->losingTrades?->numberTrades,
                'number_contracts' => $goLangDbValue?->losingTrades?->numberContracts,
                'avg_trading_time' => $goLangDbValue?->losingTrades?->avgTradingTime,
                'longest_trading_time' => $goLangDbValue?->losingTrades?->longestTradingTime,
                'percent_profitable' => $goLangDbValue?->losingTrades?->percentProfitable,
                'expectancy' => $goLangDbValue?->losingTrades?->expectancy,
            );

            AllTrade::updateOrCreate(
                $tradeCompareArray,
                $allTradeDataArray
            );

            ProfitTrade::updateOrCreate(
                $tradeCompareArray,
                $profitTradeDataArray
            );
            LosingTrade::updateOrCreate(
                $tradeCompareArray,
                $loseTradeDataArray
            );

            $goLangChartDatas = $goLangDbValue?->chartHistory;

            $chartCompareArray = array(
                'user_id' => $packagePurchase?->user_id,
                'account_id_number' => $packagePurchase?->account_id,
                'risk_management_id' => $risk_management?->id,
            );

            foreach ($goLangChartDatas as $key => $chartData) {
                $chartCompareArray['day_index'] = $chartData->dayIndex;

                $chartDataArray = array(
                    'uuid' => (string) Str::uuid(),
                    'eod_net_liq' => $chartData->eodNetLiq,
                    'eod_drawdown' => $chartData->eodDrawdown,
                    'eod_profit_target' => $chartData->eodProfitTarget,
                );

                GraphHistory::updateOrCreate(
                    $chartCompareArray,
                    $chartDataArray
                );

            }

            $goLangTradeHistory = $goLangDbValue?->tradeHistory;

            if (count($goLangTradeHistory) > 0) {

                $tradeRecordCompareArray = array(
                    'user_id' => $packagePurchase?->user_id,
                    'account' => $packagePurchase?->account_id,
                    'risk_management_id' => $risk_management?->id,
                );

                foreach ($goLangTradeHistory as $key => $tradeHistoryData) {
                    $timestamformate = $tradeHistoryData->timestamp;
                    $tradeRecordCompareArray['trade_time'] = $timestamformate;

                    $tradeRecordDataArray = array(
                        'uuid' => (string) Str::uuid(),
                        'symbol' => $tradeHistoryData?->symbol,
                        'buy' => $tradeHistoryData?->buys,
                        'sale' => $tradeHistoryData?->sells,
                        'price' => $tradeHistoryData?->price,
                        'scalePrice' => @$tradeHistoryData->scalePrice,
                    );

                    TradeRecord::updateOrCreate(
                        $tradeRecordCompareArray,
                        $tradeRecordDataArray
                    );
                }
            }

            $goLangTradePosition = $goLangDbValue?->positions;

            if (count($goLangTradePosition) > 0) {
                foreach ($goLangTradePosition as $pkey => $tradePositionData) {
                    $tradePositionCompareArray = array(
                        'user_id' => $packagePurchase?->user_id,
                        'account_id' => $packagePurchase?->account_id,
                        'risk_management_id' => $risk_management?->id,
                    );

                    $tradePositionCompareArray['index'] = (int) $pkey + 1;

                    $tradePositionDataArray = array(
                        'uuid' => (string) Str::uuid(),
                        'symbol' => $tradePositionData?->symbol,
                        'quantity' => $tradePositionData?->quantity,
                        'avgPrice' => $tradePositionData?->avgPrice,
                        'realizedPl' => $tradePositionData?->realizedPl,
                        'openPl' => $tradePositionData?->openPl,
                        'totalPl' => $tradePositionData?->totalPl,
                        'priceScale' => @$tradePositionData->priceScale,
                    );

                    TradePosition::updateOrCreate(
                        $tradePositionCompareArray,
                        $tradePositionDataArray
                    );
                }
            }

            return true;
        });

        return $status;
    }

}
