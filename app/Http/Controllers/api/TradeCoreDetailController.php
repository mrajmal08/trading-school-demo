<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trading\HistoryDashboardResource;
use App\Http\Resources\Trading\MarketDataResource;
use App\Http\Resources\Trading\MtFiveGroupResource;
use App\Http\Resources\Trading\PackagePurchaseAccountDetailResource;
use App\Http\Resources\Trading\RiskManagementListResource;
use App\Http\Resources\Trading\TradingPlatformResource;
use App\Models\MarketData;
use App\Models\MarketDataPurchaseDetail;
use App\Models\MtFiveGroup;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\RiskManagement;
use App\Models\TradingPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TradeCoreDetailController extends Controller
{
    public function marketData()
    {
        $data = MarketData::all();

        $data = MarketDataResource::collection($data);
        return response()->json([

            'status' => 200,
            'message' => "Market Data List",
            'data' => $data,
        ], 200);

        // if (empty($data)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //         'data' => "",
        //     ], 400);
        // } else {

        //     $data = MarketDataResource::collection($data);
        //     return response()->json([

        //         'status' => 200,
        //         'message' => "Market Data List",
        //         'data' => $data,
        //     ], 200);

        // }

    }

    public function tradePerformance()
    {
        $data = TradingPlatform::all();

        $data = TradingPlatformResource::collection($data);
        return response()->json([

            'status' => 200,
            'message' => "Trade Platform List",
            'data' => $data,
        ], 200);

        // if (empty($data)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //         'data' => "",
        //     ], 400);
        // } else {

        //     $data = TradingPlatformResource::collection($data);
        //     return response()->json([

        //         'status' => 200,
        //         'message' => "Trade Platform List",
        //         'data' => $data,
        //     ], 200);

        // }

    }

    public function mtFiveGroup()
    {
        $data = MtFiveGroup::all();

        $data = MtFiveGroupResource::collection($data);
        return response()->json([

            'status' => 200,
            'message' => "Mt Five Group List",
            'data' => $data,
        ], 200);

        // if (empty($data)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //         'data' => "",
        //     ], 400);
        // } else {

        //     $data = MtFiveGroupResource::collection($data);
        //     return response()->json([

        //         'status' => 200,
        //         'message' => "Mt Five Group List",
        //         'data' => $data,
        //     ], 200);

        // }

    }

    public function accountPurchaseDetail()
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $accountDetail = PackagePurchaseAccountDetail::where("user_id", $userDetail->id)->orderBy('id', 'Desc')->paginate(10);

            $packageDetail = PackagePurchaseAccountDetailResource::collection($accountDetail)->resource;
            return response()->json([

                'status' => 200,
                'message' => "Account Purchase Detail",
                'data' => $packageDetail,
            ], 200);

            // if (empty($accountDetail)) {
            //     return response()->json([
            //         'status' => 400,
            //         'message' => "Error No data found",
            //         'data' => "",
            //     ], 400);
            // } else {

            //     $packageDetail = PackagePurchaseAccountDetailResource::collection($accountDetail)->resource;
            //     return response()->json([

            //         'status' => 200,
            //         'message' => "Account Purchase Detail",
            //         'data' => $packageDetail,
            //     ], 200);

            // }

        }

    }

    public function udateMarketDataPackage(Request $request)
    {

        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $validator = Validator::make($request->all(), [
                'uuid' => 'required',
            ],
                [
                    'uuid.required' => 'Market Data required',

                ]);

            if ($validator->fails()) {
                $errorMessage = "";
                foreach ($validator->errors()->toArray() as $key => $value) {

                    foreach ($value as $key => $errorvalue) {
                        $errorMessage = $errorvalue;
                        break;
                    }

                    break;
                }

                return response()->json([
                    'status' => 401,
                    'message' => 'Validation Error',
                    'error' => $errorMessage,

                ], 400);
            }

            $marketData = MarketData::where('uuid', $request->uuid)->first();

            if (!empty($marketData)) {

                $customerEmail = $userDetail->email;
                // $customerEmail = "e@rrrr.co";
                $stripeConfigKye = config('app.stripe_secret_kye');
                $stripe = new \Stripe\StripeClient(
                    $stripeConfigKye
                );
                $customer = $stripe->customers->search([
                    'query' => 'email:\'' . $customerEmail . '\'',
                ]);

                if (empty($customer->data)) {

                    return response()->json([
                        'status' => 400,
                        'message' => 'User has no stripe id',

                    ], 400);

                } else {

                    $chargeResponse = $stripe->charges->create([
                        'customer' => $customer->data[0]["id"],
                        'amount' => ($marketData->price) * 100,
                        'currency' => 'usd',
                        'source' => $userDetail->userDetail->stripe_pay_token,
                    ]);

                    if (!empty($chargeResponse->id)) {

                        $chargeStripe = $stripe->charges->retrieve(
                            $chargeResponse->id,
                            []
                        );

                        $datatran = $stripe->balanceTransactions->retrieve(
                            $chargeStripe->balance_transaction,
                            []
                        );
                        $stipeFee = $datatran->fee;

                        if (!empty($chargeStripe->id)) {

                            $packageDetail = array(
                                "user_id" => $userDetail->id,
                                "market_data_id" => $marketData->id,
                                "account_activation_status" => 0,
                                "amp_id" => $userDetail->ampid,
                                "trader_id" => $userDetail->traderid,
                                "trader_name" => $userDetail->trader_name,
                                "account_id" => $userDetail->account_id_number,
                                "account_name" => $userDetail->account_name,
                                "package_price" => $marketData->price,
                                "stripe_amount" => (double) $stipeFee / 100,
                            );

                            $packagePurchase = MarketDataPurchaseDetail::create($packageDetail);

                            $go_lang_url = config('app.go_lang_url');
                            $go_lang_url = $go_lang_url . "futures/feeds/activate";

                            // Go Lang Url

                            $response = Http::post($go_lang_url, [
                                'cqgTraderId' => (string) $userDetail->traderid,
                                'ampId' => (string) $userDetail->ampid,
                                'feedId' => (string) $marketData->api_id,

                            ]);

                            // Go Lang Url

                            $status = $response;

                            if ($status == true) {
                                DB::transaction(function () use ($userDetail, $marketData, $packagePurchase, $response) {
                                    $userDetail->market_data_id = $marketData->id;
                                    $userDetail->save();

                                    $packagePurchase->account_activation_status = 1;
                                    $packagePurchase->log = $response;
                                    $packagePurchase->save();
                                });

                                return response()->json([
                                    'status' => 200,
                                    'message' => "Account Update successful",

                                ], 200);

                            } else {
                                return response()->json([
                                    'status' => 400,
                                    'message' => 'Market Data Update Fail',

                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'status' => 400,
                                'message' => 'Stripe Id not valid',

                            ], 400);
                        }

                    } else {
                        return response()->json([
                            'status' => 400,
                            'message' => 'Stripe Payment fail',

                        ], 400);
                    }

                }

                // $customer->data[0]["id"]
                // Call Go Lang Api

            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'No Data Found',

                ], 400);
            }

        }

    }

    public function riskManagement()
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $detail = RiskManagement::orderBy('id', 'Desc')->paginate(10);

            $riskDetailDetail = RiskManagementListResource::collection($detail)->resource;
            return response()->json([

                'status' => 200,
                'message' => "Risk Management Data",
                'data' => $riskDetailDetail,
            ], 200);

        }

    }

    public function riskManagementSingleUser()
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $detail = RiskManagement::where('user_id', $userDetail?->id)->orderBy('id', 'Desc')->paginate(10);

            $riskDetailDetail = RiskManagementListResource::collection($detail)->resource;
            return response()->json([

                'status' => 200,
                'message' => "Risk Management Data",
                'data' => $riskDetailDetail,
            ], 200);

        }

    }

    public function dahboardHistry(PackagePurchaseAccountDetail $packagePurchaseAccountDetail)
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $detail = RiskManagement::where('user_id', $userDetail?->id)->where('account_id', $packagePurchaseAccountDetail?->account_id)->first();

            if (!empty($detail)) {
                $riskDetailDetail = new HistoryDashboardResource($detail);

                return response()->json([

                    'status' => 200,
                    'message' => "History Dashboard",
                    'data' => $riskDetailDetail,
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'No User Found',

                ], 400);
            }

        }
    }
}
