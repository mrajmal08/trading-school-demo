<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyAllRecord;
use App\Models\DailyLooseRecord;
use App\Models\DailyProfitRecord;
use App\Models\DailyRecord;
use App\Models\HistoryReset;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\RiskManagement;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HistoryController extends Controller
{
    public function viewRiskManage(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status') ?? "In-Progress";
        $dates = $request->query('dates');

        $sort_by = $request->get('sort_by', 'id');
        $sort_order = $request->get('sort_order', 'desc');

        if ($sort_by == 'users.email') {
            $sort_by = User::select('email')->whereColumn('users.id', 'risk_management.user_id');
        } else if ($sort_by == 'users.userDetail.name') {
            $sort_by = UserDetail::selectRaw("concat(first_name,' ',last_name)")->whereColumn('user_details.user_id', 'risk_management.user_id');
        }

        $risks_manage = RiskManagement::where('account_status', $status)->with(['user' => function ($query) {
            return $query->with('userDetail');
        }, 'cardChallenge'])
            ->when($search, function ($query, $search) {
                return $query->where('trader_name', $search)
                    ->orWhere('trader_id', $search)
                    ->orWhere('account_id', $search)
                    ->orWhere('account_name', $search)
                    ->orWhere('amp_id', $search);
            })
            ->when($dates, function ($query, $dates) {
                $dates = explode("-", $dates);
                $startDate = date("Y-m-d", strtotime($dates['0']));
                $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'] . '+ 1 day')) : '');
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($search, function ($query, $search) {
                return $query->orWhereHas('user.userDetail', function ($query) use ($search) {
                    $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ")
                        ->orWhere('email', $search);
                });
            })
            ->orderBy($sort_by, $sort_order)->paginate(10)->withQueryString();
        $sort_by = $request->get('sort_by');

        return view('pages.admin.risk-manage-view', \compact('risks_manage', 'search', 'status', 'sort_by', 'sort_order', 'dates'));
    }

    public function challengeHistories(Request $request)
    {
        $search = $request->query('search');
        $dates = $request->query('dates');
        $status = $request->query('status');
        // $sort_by = $request->get('sort_by', 'id');
        // $sort_order = $request->get('sort_order', 'desc');

        // if ($sort_by == 'users.email') {
        //     $sort_by = User::select('email')->whereColumn('users.id', 'risk_management.user_id');
        // } else if ($sort_by == 'users.userDetail.name') {
        //     $sort_by = UserDetail::selectRaw("concat(first_name,' ',last_name)")->whereColumn('user_details.user_id', 'risk_management.user_id');
        // }

        $histories = PackagePurchaseAccountDetail::with(['user' => function ($query) {
            $query->with("userDetail");
        }, 'cardChallenge'])
            ->when($search, function ($query, $search) {
                return $query->where('trader_name', $search)
                    ->orWhere('trader_id', $search)
                    ->orWhere('account_id', $search)
                    ->orWhere('account_name', $search)
                    ->orWhere('amp_id', $search);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('account_activation_status', $status == 'Live' ? 1 : 0);
            })
            ->when($dates, function ($query, $dates) {
                $dates = explode("-", $dates);
                $startDate = date("Y-m-d", strtotime($dates['0']));
                $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'] . '+ 1 day')) : '');
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($search, function ($query, $search) {
                return $query->orWhereHas('user.userDetail', function ($query) use ($search) {
                    $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ")
                        ->orWhere('email', $search);
                });
            })
            ->orderBy('id', 'desc')->paginate(10)->withQueryString();
        // ->orderBy($sort_by, $sort_order)->paginate(10)->withQueryString();
        // $sort_by = $request->get('sort_by');

        return view('pages.admin.historical-challenges', \compact('histories', 'status'));
    }

    // public function challengeHistories(Request $request)
    // {
    //     $search = $request->query('search');
    //     $dates = $request->query('dates');
    //     $histories = HistoryReset::with(['user' => function ($query) {
    //         return $query->with('userDetail');
    //     }, 'cardChallenge'])->when($search, function ($query, $search) {
    //         return $query->where('trader_name', $search)
    //             ->orWhere('trader_id', $search)
    //             ->orWhere('account_id', $search)
    //             ->orWhere('account_name', $search)
    //             ->orWhere('amp_id', $search)
    //             ->orWhere('account_status', $search);
    //     })
    //         ->when($dates, function ($query, $dates) {
    //             $dates = explode("-", $dates);
    //             $startDate = date("Y-m-d", strtotime($dates['0']));
    //             $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');
    //             return $query->whereBetween('created_at', [$startDate, $endDate]);
    //         })
    //         ->when($search, function ($query, $search) {
    //             return $query->orWhereHas('user.userDetail', function ($query) use ($search) {
    //                 $query->whereRaw("concat(first_name,' ',last_name) like '%" . $search . "%'")
    //                     ->orWhere('email', $search);
    //             });
    //         })
    //         ->orderBy('id', 'DESC')->paginate(10);

    //     return view('pages.admin.historical-challenges', \compact('histories'));
    // }

    public function getResetHistory(Request $request, $id)
    {
        $search = $request->query('search');
        $dates = $request->query('dates');

        $histories = HistoryReset::where('user_id', $id)
            ->when($dates, function ($query) use ($dates) {
                $dates = explode("-", $dates);
                $startDate = date("Y-m-d", strtotime($dates['0']));
                $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->orderBy('id', 'DESC')->paginate(10);

        return view('pages.admin.reset-history', \compact('histories', 'id'));
    }

    public function getUserChallengesHistory(PackagePurchaseAccountDetail $packagePurchaseAccountDetail)
    {
        $histories = RiskManagement::where('user_id', $packagePurchaseAccountDetail->user_id)->where('account_id', $packagePurchaseAccountDetail->account_id)->first();
        return view('pages.admin.user_challenges', \compact('histories'));
    }

    public function getRiskChallengeDetails($id)
    {
        $risk = RiskManagement::find($id);
        return \view('pages.admin.risk-manage-details', \compact('risk'));
    }

    public function storeDailyRecord()
    {
        $alluserList = User::all();
        $currentDate = date('Y-m-d');

        foreach ($alluserList as $key => $userValue) {
            if ((!empty($userValue->account_id_number)) && (!empty($userValue->traderid)) && (!empty($userValue->account_name)) && ($userValue?->userDetail?->status == 1)) {
                $dashBoardUrl = config('app.go_lang_url');

                $accountID = $userValue->account_id_number;
                $uuid = $userValue->uuid;
                $tradeID = $userValue->traderid;
                $ampID = $userValue->ampid;
                $userId = $userValue->id;
                $acountName = $userValue->account_name;

                $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $accountID . "/" . $tradeID . "/" . $acountName . "?startDate=" . $currentDate . "&endDate=" . $currentDate;

                $response = Http::get($dashBoardUrl);

                $goLangDbValue = $response->object();

                if (($response->successful() == true) && ($response->failed() == false) && (!empty($goLangDbValue)) && ($goLangDbValue->status == "success")) {

                    $goLangDbValue = $goLangDbValue->data;
                    $dbInsert = $this->dailyStoreDashboardData($goLangDbValue, $userValue, $currentDate);
                }

            }
        }
    }

    public function dailyStoreDashboardData($goLangDbValue, $userDetail, $date)
    {

        DB::transaction(function () use ($goLangDbValue, $userDetail, $date) {

            $packagePurchase = PackagePurchaseAccountDetail::orderBy('id', 'DESC')->where('account_id', $userDetail?->account_id_number)->first();

            $dailyUnique['user_id'] = $packagePurchase?->user_id;
            $dailyUnique['account_id'] = $packagePurchase?->account_id;
            $dailyUnique['current_date'] = $date;

            $dailyStoreData['amp_id'] = $packagePurchase?->amp_id;
            $dailyStoreData['trader_id'] = $packagePurchase?->trader_id;
            $dailyStoreData['trader_name'] = $packagePurchase?->trader_name;
            // $dailyStoreData['account_id'] = $packagePurchase?->account_id;
            $dailyStoreData['account_name'] = $packagePurchase?->account_name;
            $dailyStoreData['card_challenge_id'] = $packagePurchase?->card_challenge_id;
            $dailyStoreData['package_price'] = $packagePurchase?->package_price;
            $dailyStoreData['account_status'] = $packagePurchase?->account_status;

            $dailyStoreData['open_contracts'] = $goLangDbValue->openContracts;
            $dailyStoreData['current_daily_pl'] = $goLangDbValue->currentDailyPl;
            $dailyStoreData['trading_day'] = (int) $goLangDbValue->tradingDay;
            $dailyStoreData['net_liq_value'] = $goLangDbValue->netLiqValue;
            $dailyStoreData['sodbalance'] = $goLangDbValue->sodbalance;
            $dailyStoreData['rule_1_value'] = $goLangDbValue->rule1Value;
            $dailyStoreData['rule_1_maximum'] = $goLangDbValue->rule1Maximum;
            $dailyStoreData['rule_2_value'] = $goLangDbValue->rule2Value;
            $dailyStoreData['rule_2_maximum'] = $goLangDbValue->rule2Maximum;
            $dailyStoreData['rule_3_value'] = $goLangDbValue->rule3Value;
            $dailyStoreData['rule_3_maximum'] = $goLangDbValue->rule3Maximum;

            $dailyStoreData['dashboard_id'] = $goLangDbValue->id;
            $dailyStoreData['isActive'] = $goLangDbValue->isActive;
            $dailyStoreData['isPrimary'] = $goLangDbValue->isPrimary;
            $dailyStoreData['accountSize'] = $goLangDbValue->accountSize;
            $dailyStoreData['isLocked'] = $goLangDbValue->isLocked;
            $dailyStoreData['isEmpty'] = $goLangDbValue->isEmpty;
            $dailyStoreData['isMaybeLocked'] = $goLangDbValue->isMaybeLocked;
            $dailyStoreData['balance'] = $goLangDbValue->balance;
            $dailyStoreData['dailyLossLimit'] = $goLangDbValue->dailyLossLimit;
            $dailyStoreData['currentDrawdown'] = $goLangDbValue->currentDrawdown;
            $dailyStoreData['drawdownLimit'] = $goLangDbValue->drawdownLimit;
            $dailyStoreData['rule1Enabled'] = $goLangDbValue->rule1Enabled;
            $dailyStoreData['rule1Key'] = $goLangDbValue->rule1Key;
            $dailyStoreData['rule1Failed'] = $goLangDbValue->rule1Failed;
            $dailyStoreData['rule2Enabled'] = $goLangDbValue->rule2Enabled;
            $dailyStoreData['rule2Key'] = $goLangDbValue->rule2Key;
            $dailyStoreData['rule2Failed'] = $goLangDbValue->rule2Failed;
            $dailyStoreData['rule3Enabled'] = $goLangDbValue->rule3Enabled;
            $dailyStoreData['rule3Key'] = $goLangDbValue->rule3Key;
            $dailyStoreData['rule3Failed'] = $goLangDbValue->rule3Failed;
            $dailyStoreData['profitTarget'] = $goLangDbValue->profitTarget;
            $dailyStoreData['minimumDays'] = $goLangDbValue->minimumDays;

            $daily_record = DailyRecord::updateOrCreate(
                $dailyUnique,
                $dailyStoreData
            );

            $tradeCompareArray = array(
                'user_id' => $packagePurchase?->user_id,
                'account_id_number' => $packagePurchase?->account_id,
                'daily_record_id' => $daily_record?->id,
                'current_date' => $date,
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

            DailyAllRecord::updateOrCreate(
                $tradeCompareArray,
                $allTradeDataArray
            );

            DailyProfitRecord::updateOrCreate(
                $tradeCompareArray,
                $profitTradeDataArray
            );
            DailyLooseRecord::updateOrCreate(
                $tradeCompareArray,
                $loseTradeDataArray
            );

        });
    }
}
