<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewUserRegistration;
use App\Http\Controllers\Controller;
use App\Mail\ChallengeLive;
use App\Models\AllTrade;
use App\Models\CardChallenge;
use App\Models\ChallengeMarket;
use App\Models\GraphHistory;
use App\Models\HistoryReset;
use App\Models\LosingTrade;
use App\Models\MarketData;
use App\Models\MarketDataPurchaseDetail;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\PackagePurchaseMarket;
use App\Models\ProfitTrade;
use App\Models\TradeRecord;
use App\Models\TradingDetail;
use App\Models\User;
use App\Models\UserMarketData;
use Carbon\Carbon;
use Exception;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use stdClass;

class AccountController extends Controller
{
    // public function index(Request $request)
    // {
    //     $search = $request->query('search');
    //     $status = $request->query('account_status');
    //     $user = $request->query('user');
    //     $dates = explode("-", $request->query('dates'));

    //     $startDate = date("Y-m-d", strtotime($dates['0']));
    //     $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');

    //     $userList = User::with('userDetail')->get();
    //     $allAccounts = DB::table('package_purchase_account_details')
    //         ->select(
    //             'package_purchase_account_details.account_name',
    //             'package_purchase_account_details.account_id',
    //             'package_purchase_account_details.amp_id',
    //             'package_purchase_account_details.trader_id',
    //             'package_purchase_account_details.trader_name',
    //             'package_purchase_account_details.package_price',
    //             'package_purchase_account_details.account_status',
    //             'package_purchase_account_details.card_challenge_id',
    //             'package_purchase_account_details.account_activation_status',
    //             'user_details.user_id',
    //             'users.email',
    //             'user_details.first_name',
    //             'user_details.last_name',
    //             'card_challenges.title',
    //             'package_purchase_account_details.created_at',
    //             'package_purchase_account_details.id as pId',
    //             'package_purchase_account_details.uuid',
    //         )
    //         ->join('users', 'users.id', '=', 'package_purchase_account_details.user_id')
    //         ->join('user_details', 'user_details.user_id', '=', 'users.id')
    //         ->join('card_challenges', 'card_challenges.id', '=', 'package_purchase_account_details.card_challenge_id')
    //         ->orWhere(function ($query) use ($search) {
    //             $query->orWhere('users.email', $search);
    //             $query->orWhere('package_purchase_account_details.account_name', $search);
    //             $query->orWhere('package_purchase_account_details.account_id', $search);
    //             $query->orWhere('package_purchase_account_details.package_price', $search);
    //             $query->orWhere('package_purchase_account_details.account_activation_status', "=", $search == 'Live' ? 1 : 0);
    //         })->orWhere(DB::raw('CONCAT(user_details.first_name," ",user_details.last_name)'), $search)
    //         ->orWhere('card_challenges.title', $search)
    //         ->when($endDate, function ($query) use ($startDate, $endDate) {
    //             return $query->whereBetween('package_purchase_account_details.created_at', [$startDate, $endDate]);
    //         })->orderBy('pId', 'desc')
    //         ->paginate(10)->withQueryString();

    //     return view('pages.admin.account-list', compact(['allAccounts', 'userList']));
    // }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $user = $request->query('user');
        $dates = $request->query('dates');

        $allAccounts = PackagePurchaseAccountDetail::with(['user' => function ($query) {
            return $query->with('userDetail');
        }, 'cardChallenge'])
            ->when($search, function ($query) use ($search) {
                return $query->where('trader_name', $search)
                    ->orWhere('account_name', $search)
                    ->orWhere('account_id', $search)
                    ->orWhere('trader_id', $search);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('account_activation_status', $status == 'Live' ? 1 : 0);
            })
            ->when($dates, function ($query) use ($dates) {
                $dates = explode("-", $dates);
                $startDate = date("Y-m-d", strtotime($dates['0']));
                $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'] . '+ 1 day')) : '');
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($search, function ($query) use ($search) {
                return $query->orWhereHas('user.userDetail', function ($query) use ($search) {
                    return $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ")
                        ->orWhere('email', $search);
                });
            })
            ->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('pages.admin.account-list', compact(['allAccounts', 'status']));
    }

    public function accountListLog(Request $request)
    {

        $allAccounts = DB::table('subscribe_logs')
            ->select(
                'subscribe_logs.response',
            )
            ->where('subscribe_logs.package_purchase_account_detail_id', $request->userId)
            ->first();
        return $allAccounts;
    }

    public function packagePurchaseAccountActivate(PackagePurchaseAccountDetail $packagePurchaseAccountDetail)
    {

        $user = User::find($packagePurchaseAccountDetail->user_id);
        $challenge = CardChallenge::where('id', $packagePurchaseAccountDetail->card_challenge_id)->first();

        $go_lang_url = config('app.go_lang_url');
        $go_lang_url = $go_lang_url . "futures/user/registration";
        $cqg_market = config('app.cqg_market');

        if ((!empty($challenge)) && (!empty($user))) {
            $marketData = MarketData::find($challenge->market_data_id)->first();
            if (!empty($user->account_id_number)) {
                $response = Http::post($go_lang_url, [
                    // 'RequestId' => $user->id, // must be remove tomorrow
                    'firstName' => $user->userDetail?->first_name,
                    'lastName' => $user->userDetail?->last_name,
                    'email' => $user->email,
                    'country' => 'United States of America',
                    'state' => 'New York',

                    'risk_account_clone_id' => (string) $challenge->clone_id,
                    'starting_balance' => (int) $challenge->buying_power,
                    'cqg_market' => $cqg_market,
                    'market_data_feed_id' => (string) $marketData->api_id,

                    'accountId' => (string) $user->account_id_number,
                    'ampid' => (string) $user->ampid,
                    'traderid' => $user->traderid,
                    "accountSize" => 50000,

                ]);
            } else {

                $response = Http::post($go_lang_url, [
                    // 'RequestId' => $user->id, // must be remove tomorrow
                    'firstName' => $user->userDetail?->first_name,
                    'lastName' => $user->userDetail?->last_name,
                    'email' => $user->email,
                    'country' => 'United States of America',
                    'state' => 'New York',
                    'cqg_market' => $cqg_market,
                    'market_data_feed_id' => (string) $marketData->api_id,
                    'risk_account_clone_id' => (string) $challenge->clone_id,
                    'starting_balance' => (int) $challenge->buying_power,
                    'cqg_market' => $cqg_market,
                    "accountSize" => 50000,

                ]);
            }

            // dd($response->body(), $response->object(), $response->collect());

            $getData = $response->collect();

            Log::info(['User_Active_body' => $response->body()]);

            if (($response->failed() == false) && (!empty($getData))) {

                if (!empty($getData["new_trading_account"])) {

                    $ampId = $user->ampid;
                    $accountID = (!empty($getData["new_trading_account"]["new_account_id"])) ? $getData["new_trading_account"]["new_account_id"] : "";
                    $accountName = (!empty($getData["new_trading_account"]["new_account_name"])) ? $getData["new_trading_account"]["new_account_name"] : "";
                    $tradeId = (!empty($getData["new_trading_account"]["trader_id"])) ? $getData["new_trading_account"]["trader_id"] : "";
                    $request_id = (!empty($getData["new_trading_account"]["request_id"])) ? $getData["new_trading_account"]["request_id"] : "";

                    $tradeName = (!empty($user->trader_name)) ? $user->trader_name : ""; // must be change
                    $customerid = (!empty($user->new_customer_id)) ? $user->new_customer_id : ""; // must be change

                    if ((empty($ampId)) || (empty($getData["new_trading_account"]["new_account_id"])) || (empty($getData["new_trading_account"]["trader_id"]))) {
                        $account_activation_status = 0;
                    } else {
                        $account_activation_status = 1;
                    }
                } else {

                    $ampId = (!empty($getData["new_user"]['new_customer_id'])) ? $getData["new_user"]['new_customer_id'] : "";
                    $accountID = (!empty($getData["new_user"]["new_account"]["id"])) ? $getData["new_user"]["new_account"]["id"] : "";
                    $accountName = (!empty($getData["new_user"]["new_account"]["name"])) ? $getData["new_user"]["new_account"]["name"] : "";
                    $tradeId = (!empty($getData["new_user"]["new_trader"]["id"])) ? $getData["new_user"]["new_trader"]["id"] : "";
                    $tradeName = (!empty($getData["new_user"]["new_trader"]["name"])) ? $getData["new_user"]["new_trader"]["name"] : "";
                    $request_id = (!empty($getData["new_user"]["request_id"])) ? $getData["new_user"]["request_id"] : "";
                    $customerid = (!empty($getData["new_user"]["new_customer_id"])) ? $getData["new_user"]["new_customer_id"] : "";

                    if ((empty($getData["new_user"]['new_customer_id'])) || (empty($getData["new_user"]["new_account"]["id"])) || (empty($getData["new_user"]["new_trader"]["id"]))) {
                        $account_activation_status = 0;
                    } else {
                        $account_activation_status = 1;
                    }
                }

                $packageDetail = array(
                    "request_id" => $request_id,
                    "amp_id" => $ampId,
                    "trader_id" => $tradeId,
                    "trader_name" => $tradeName,
                    "account_activation_status" => $account_activation_status,
                    "account_id" => $accountID,
                    "account_name" => $accountName,
                    "new_customer_id" => $customerid,
                    "package_price" => $challenge->price,
                    "account_status" => "In-Progress",
                );

                $packagePurchaseAccountDetail->update($packageDetail);

                return Redirect::back()->with(['success' => 'Account Activation successful']);
            } else {
                return Redirect::back()->with(['error' => 'Cqg Server Not Responding']);
            }
        } else {
            return Redirect::back()->with(['error' => 'Account Activation not successful']);
        }
    }

    public function accountReset(Request $request)
    {
        $user = User::where('uuid', $request->userid)->get()->first();
        $go_lang_url = config('app.go_lang_url');
        $go_lang_url = $go_lang_url . "futures/user/account/deactivate";
        $cqg_market = config('app.cqg_market');

        // $packagePurchase = PackagePurchaseAccountDetail::orderBy('id', 'DESC')->where("user_id", $user->id)->first();
        $packagePurchase = PackagePurchaseAccountDetail::orderBy('id', 'DESC')->where("user_id", $user->id)->where("account_id", $user?->account_id_number)->first();

        if (empty($packagePurchase->card_challenge_id)) {
            return Redirect::back()->with(['error' => 'No challenge found for this user']);
        }

        $challenge = CardChallenge::where('id', $packagePurchase->card_challenge_id)->first();
        $marketData = MarketData::find($user->market_data_id);
        // dd($marketData);
        if (empty($marketData)) {
            $marketData = new MarketData;
            $marketData->api_id = "";
        }
        if (empty($challenge)) {
            $challenge = new CardChallenge;
            $challenge->clone_id = "";
            $challenge->buying_power = "";
        }

        if (!empty($user->account_id_number)) {
            $response = Http::post($go_lang_url, [
                'accountId' => (string) $user->account_id_number,
                'traderid' => (string) $user->traderid,
                'ampid' => (string) $user->ampid,
                'cqg_market' => $cqg_market,
                'email' => $user->email,
                'firstName' => $user->userDetail?->first_name,
                'lastName' => $user->userDetail?->last_name,
                'country' => 'United States of America',
                'state' => 'New York',
                'market_data_feed_id' => (string) $marketData->api_id,
                'risk_account_clone_id' => (string) $challenge->clone_id,
                'starting_balance' => (int) $challenge->buying_power,
                "accountSize" => 50000,

            ]);
        } else {

            $response = Http::post($go_lang_url, [

                'cqg_market' => $cqg_market,
                'email' => $user->email,
                'firstName' => $user->userDetail?->first_name,
                'lastName' => $user->userDetail?->last_name,
                'country' => 'United States of America',
                'state' => 'New York',
                'market_data_feed_id' => (string) $marketData->api_id,
                'risk_account_clone_id' => (string) $challenge->clone_id,
                'starting_balance' => (int) $challenge->buying_power,
                "accountSize" => 50000,
            ]);
        }

        $getData = $response->collect();

        if ($getData->count() > 0) {

            if (!empty($getData["new_trading_account"])) {
                $accountID = (!empty($getData["new_trading_account"]["new_account_id"])) ? $getData["new_trading_account"]["new_account_id"] : "";
            } else {
                $accountID = (!empty($getData["new_user"]["new_account"]["id"])) ? $getData["new_user"]["new_account"]["id"] : "";
            }

            // New code for extra market data active
            $extraMarketData = ChallengeMarket::where('card_challenge_id', $challenge->id)->get();
            Log::info(['extraMarketData' => $extraMarketData]);
            if ($extraMarketData->isNotEmpty() && $extraMarketData->count() > 0) {
                foreach ($extraMarketData as $marketkey => $marketDataValue) {
                    $packagePurchaseMarket[$marketkey] = array(
                        "uuid" => (string) Str::uuid(),
                        "package_purchase_account_detail_id" => $packagePurchase->id,
                        "market_data_id" => $marketDataValue->market_data_id,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    );

                    $usrExtraMarket[$marketkey] = array(
                        "uuid" => (string) Str::uuid(),
                        "user_id" => $user->id,
                        "market_data_id" => $marketDataValue->market_data_id,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    );
                }
                PackagePurchaseMarket::where('package_purchase_account_detail_id', $packagePurchase->id, )->delete();
                UserMarketData::where('user_id', $user->id)->delete();
                PackagePurchaseMarket::insert($packagePurchaseMarket);
                UserMarketData::insert($usrExtraMarket);
            }

            // New code for extra market data active

            //New Code For requirement
            $historyData['package_purchase_account_detail_id'] = $packagePurchase?->id;
            $historyData['user_id'] = $packagePurchase?->user_id;
            $historyData['amp_id'] = $packagePurchase?->amp_id;
            $historyData['trader_id'] = $packagePurchase?->trader_id;
            $historyData['trader_name'] = $packagePurchase?->trader_name;
            $historyData['account_id'] = $packagePurchase?->account_id;
            $historyData['account_name'] = $packagePurchase?->account_name;
            $historyData['card_challenge_id'] = $packagePurchase?->card_challenge_id;
            $historyData['package_price'] = $packagePurchase?->package_price;
            $historyData['account_status'] = $packagePurchase?->account_status;

            $dashBoardUrl = config('app.go_lang_url');

            $startDate = $user?->userDetail?->start_date;
            $newStartDate = Carbon::parse($startDate);
            $endData = date("Y-m-d");

            $newEndData = Carbon::parse($endData);
            $result = $newStartDate->eq($newEndData);

            $endData = $newEndData->addDay(1);
            if ($result) {
                $startDate = Carbon::parse($startDate)->subDay(1);
            }

            $oldAccountID = $user->account_id_number;
            $tradeID = $user->traderid;
            $acountName = $user->account_name;

            $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $oldAccountID . "/" . $tradeID . "/" . $acountName . "?startDate=" . $startDate . "&endDate=" . $endData;

            $response = Http::get($dashBoardUrl);

            $goLangDbValue = $response->object();

            $status = $goLangDbValue?->status;

            if ($status == "success") {
                $goLangValue = $goLangDbValue?->data;

                $historyData['open_contracts'] = $goLangValue->openContracts;
                $historyData['current_daily_pl'] = $goLangValue->currentDailyPl;

                $historyData['trading_day'] = (int) $goLangValue->tradingDay;
                $historyData['net_liq_value'] = $goLangValue->netLiqValue;
                $historyData['sodbalance'] = $goLangValue->sodbalance;
                $historyData['rule_1_value'] = $goLangValue->rule1Value;
                $historyData['rule_1_maximum'] = $goLangValue->rule1Maximum;
                $historyData['rule_2_value'] = $goLangValue->rule2Value;
                $historyData['rule_2_maximum'] = $goLangValue->rule2Maximum;
                $historyData['rule_3_value'] = $goLangValue->rule3Value;
                $historyData['rule_3_maximum'] = $goLangValue->rule3Maximum;
            }

            //New Code For requirement

            if (!empty($accountID)) {
                DB::transaction(function () use ($user, $accountID, $historyData) {
                    if (count($historyData) > 0) {
                        HistoryReset::create($historyData);
                    }

                    User::where('account_id_number', $user->account_id_number)->update(['account_id_number' => $accountID]);
                    AllTrade::where('account_id_number', $user->account_id_number)->update(['account_id_number' => $accountID]);
                    GraphHistory::where('account_id_number', $user->account_id_number)->update(['account_id_number' => $accountID]);
                    LosingTrade::where('account_id_number', $user->account_id_number)->update(['account_id_number' => $accountID]);
                    ProfitTrade::where('account_id_number', $user->account_id_number)->update(['account_id_number' => $accountID]);
                    MarketDataPurchaseDetail::where('account_id', $user->account_id_number)->update(['account_id' => $accountID]);
                    PackagePurchaseAccountDetail::where('account_id', $user->account_id_number)->update(['account_id' => $accountID]);
                    TradeRecord::where('account', $user->account_id_number)->update(['account' => $accountID]);
                    TradingDetail::where('account_id_number', $user->account_id_number)->update(['account_id_number' => $accountID]);
                });

                return Redirect::back()->with(['success' => 'Account Reset successful']);
            } else {
                return Redirect::back()->with(['error' => 'Account Reset not successful']);
            }
        } else {
            return Redirect::back()->with(['error' => 'No Data Get From Go Lang API']);
        }
    }

    public function accountBalanceUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'addwithdraw' => 'required',
            'userid' => 'required',
            'currentbalance' => 'required',
        ]);

        if ($validator->fails()) {

            return Redirect::back()->with(['error' => "Error in amount"]);
        } else {

            if ($request->addwithdraw == "add") {
                // $sendBalance = (double) $request->currentbalance + (double) $request->amount;
                $sendBalance = (float) $request->amount;
            }

            // if ($request->addwithdraw == "withdraw") {
            //     $sendBalance = (double) $request->currentbalance - (double) $request->amount;
            // }
            $userDetail = User::findOrFail($request->userid);
            $go_lang_url = config('app.go_lang_url');

            $go_lang_url = $go_lang_url . "futures/user/account/upgrade/" . $userDetail->account_id_number;

            // dd($go_lang_url);

            $response = Http::post($go_lang_url, [
                'amount' => (int) $sendBalance,
                'isActive' => true,
                'accTraderID' => (string) $userDetail->traderid,
                'accName' => (string) $userDetail->account_name,

            ]);
            // dd($response->object());

            if ($response == true) {
                return Redirect::back()->with(['success' => 'Account Balance Updated']);
            } else {
                return Redirect::back()->with(['error' => 'Account Balance Update Failed']);
            }
        }
    }

    public function forcedAccount(PackagePurchaseAccountDetail $packagePurchaseAccountDetail)
    {
        $go_lang_url = config('app.go_lang_url');
        $go_lang_url = $go_lang_url . "futures/user/account/details";

        $user = User::find($packagePurchaseAccountDetail->user_id);
        $response = Http::post($go_lang_url, [
            'email' => $user->email,
            // 'email' => 'janiho8644@ngopy.com',
        ]);

        $getData = $response->object();

        if ((!empty($getData->cqg_account)) || (!empty($getData->cqg_trader)) || (!empty($getData->cqg_customer))) {
            // dd($response->object());
            $status = DB::transaction(function () use ($getData, $user, $packagePurchaseAccountDetail) {
                $userUpdate = array(
                    "account_id_number" => $getData->cqg_account,
                    "traderid" => $getData->cqg_trader,
                    "ampid" => $getData->cqg_customer,
                    "market_data_id" => 1,
                );

                $packagePurchase = array(
                    "account_id" => $getData->cqg_account,
                    "trader_id" => $getData->cqg_trader,
                    "amp_id" => $getData->cqg_customer,
                    "account_activation_status" => 1,
                );

                $user->update($userUpdate);
                $packagePurchaseAccountDetail->update($packagePurchase);

                return true;
            });

            if ($status == true) {
                return Redirect::back()->with(['success' => 'Data updated']);
            } else {
                return Redirect::back()->with(['error' => 'Data not updated']);
            }
        } else {
            return Redirect::back()->with(['error' => 'There is no CQG detail for this email']);
        }
    }

    public function activeLiveAccount(PackagePurchaseAccountDetail $packagePurchaseAccountDetail)
    {
        $userDetail = User::find($packagePurchaseAccountDetail->user_id);
        $updateData = array(
            "account_status" => "Live Trading",
        );
        $packagePurchaseAccountDetail->update($updateData);

        $mailObject = new stdClass;
        $mailObject->name = $userDetail?->userDetail?->first_name . ' ' . $userDetail?->userDetail?->last_name;
        try {
            Mail::to($userDetail?->email)->send(new ChallengeLive($mailObject));
        } catch (Exception $e) {
            $e->getMessage();
        }

        $notification = $this->notification($userDetail);

        return Redirect::back()->with(['success' => 'Data updated']);
    }

    public function notification($user)
    {
        $data['image'] = "";
        $data['head_title'] = "challenge is live now";
        $data['message'] = "You can start your journey towards becoming a successful trader right away! Your challenge is live now. Tap to see details. ";
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
}
