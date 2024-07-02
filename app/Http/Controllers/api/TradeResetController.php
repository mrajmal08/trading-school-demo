<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\SubscribeLog;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TradeResetController extends Controller
{
    public function cqgMasterResetPassword()
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $go_lang_url = config('app.go_lang_url');
            $go_lang_url = $go_lang_url . "futures/user/password/reset";

            $response = Http::post($go_lang_url, [
                "cqgTraderId" => $userDetail->traderid,
                "cqgAmpId" => $userDetail->ampid,
            ]);

            $getData = $response->collect();
            if ($getData) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Request Successful. An Email is send to your email address',

                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Request not Successful',

                ], 400);
            }
            //Call Go Lang Api

        }
    }

    public function dangerZone()
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            // $go_lang_url = config('app.go_lang_url');
            // $go_lang_url = $go_lang_url . "futures/user/market-trader/disable";

            //Call Go Lang Api

            if ((!empty($userDetail->traderid)) && (!empty($userDetail->market_data_id)) && (!empty($userDetail->account_id_number))) {
                // $response = Http::post($go_lang_url, [
                //     "traderId" => (string) $userDetail->traderid,
                //     "marketId" => (string) $userDetail->market_data_id,
                //     "accountId" => (string) $userDetail->account_id_number,
                // ]);

                $subsCribeLog = SubscribeLog::orderBy('id', 'desc')->where("user_id", $userDetail->id)->first();
                $stripeConfigKye = config('app.stripe_secret_kye');

                $stripe = new \Stripe\StripeClient(
                    $stripeConfigKye
                );

                $cancelSubscription = $stripe->subscriptions->cancel(
                    $subsCribeLog->stripe_subscription_id,
                    []
                );
                if (!empty($cancelSubscription)) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Current Package Subscription Deactivate',

                    ], 200);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Current Package Subscription Deactivate but Stripe subscriptions cancel not done',

                    ], 200);
                }

                // } else {
                //     return response()->json([
                //         'status' => 400,
                //         'message' => "Current Package Subscription Deactivate",

                //     ], 400);
                // }

            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "You don't any package to deactivated ",

                ], 400);
            }

        }
    }

    public function changePaymentType(Request $request)
    {

        $user = Auth::guard('api')->user();

        if (empty($user)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $validator = Validator::make($request->all(), [
                'card_number' => 'required',
                'expiry_date' => 'required',
                'cvc' => 'required',

            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status' => 400,
                    'message' => 'Error in Card Information',

                ], 400);

            } else {

                $stripeConfigKye = config('app.stripe_secret_kye');
                $stripe = new \Stripe\StripeClient(
                    $stripeConfigKye
                );

                $query = $stripe->customers->search([
                    'query' => 'email:\'' . $user->email . '\'',
                ]);

                $expirydate = explode("/", $request->expiry_date);
                $expiryMonth = $expirydate[0];
                $expiryYear = "20" . $expirydate[1];

                $stripeToken = $stripe->tokens->create([
                    // 'type' => 'card',
                    'card' => [
                        'number' => $request->card_number,
                        'exp_month' => $expiryMonth,
                        'exp_year' => (int) $expiryYear,
                        'cvc' => $request->cvc,
                    ],
                ]);
                // dd( $stripeToken);
                if (empty($stripeToken)) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'New Payment Method not Created',

                    ], 400);
                } else {
                    $stripePaymentMethodId = $stripeToken->id;

                    $cardSource = $stripe->customers->createSource(
                        $query->data[0]->id,
                        ['source' => $stripeToken->id]
                    );

                    $userDetail = UserDetail::where('user_id', $user->id)->first();
                    $userDetail->stripe_pay_token = $cardSource->id;
                    $userDetail->save();

                    return response()->json([
                        'status' => 200,
                        'message' => 'Payment Type Change Successful',

                    ], 200);

                }

            }

        }
    }

    public function checkCqgAccount()
    {
        $user = Auth::guard('api')->user();

        $go_lang_url = config('app.go_lang_url');

        if (empty($user)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $go_lang_url_status = $go_lang_url . "futures/user/cqg/status";
            $response = Http::get($go_lang_url_status);

Log::info("**********CQG STATUS********");
Log::info(json_encode($response->object(), true));
Log::info("*********HERE*************");
            $getStatusData = $response->object();

            if (($response->successful() == true) && (!empty($getStatusData))) {

                $getAllData = $getStatusData;

                if ($getAllData->cqg_connection_status == 1) {

                    $go_lang_url_eligible = $go_lang_url . "futures/user/verify/email";

                    $newResponse = Http::post($go_lang_url_eligible, [
                        "email" => $user->email,
                        // "email" => "janiho8644@ngopy.com",
                    ]);

                    $getData = $newResponse->object();

                    if (($newResponse->successful() == true) && (!empty($getData))) {

                        if ((empty($user->account_id_number)) && ($getData->user_exists == false)) {

                            return response()->json([
                                'status' => true,
                                'message' => 'Eligible to Create Account',
                            ], 200);

                        }

                        if ((!empty($user->account_id_number)) && ($getData->user_exists == true)) {

                            return response()->json([
                                'status' => true,
                                'message' => 'Eligible to Create Account',
                            ], 200);

                        }

                        if ((empty($user->account_id_number)) && ($getData->user_exists == true)) {

                            return response()->json([
                                'status' => false,
                                'message' => 'This Email already existed in cqg Server',
                            ], 400);

                        }

                        return response()->json([
                            'status' => false,
                            'message' => 'Not Eligible',
                        ], 400);

                    } else {
                        return response()->json([
                            'status' => 400,
                            'message' => 'No Response',
                            'resData' => $getData,

                        ], 400);
                    }

                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "challenges can only be activated during Globex market hours",
                        // 'message' => "CQG Official Maintnenace Windows are: Weekdays from 4-4:30pm US Chicago time Weekends from Fri 6pm to Sun 9am US Chicago time",
                    ], 200);
                }
            } else {

                return response()->json([
                    'status' => 400,
                    'message' => 'No Response',
                    'resData' => $getStatusData,

                ], 400);

            }

        }
    }

    public function cqgStatus()
    {
        $user = Auth::guard('api')->user();

        if (empty($user)) {
Log::info("*******************************IF*****************");
            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {
            $go_lang_url = config('app.go_lang_url');
            $go_lang_url = $go_lang_url . "futures/user/cqg/status";

            $response = Http::get($go_lang_url);
Log::info("**********CQG STATUS********");
            $getData = $response->collect();
Log::info(json_encode($getData), true);
            if (($response->successful() == true) && (!empty($getData))) {

                if ($getData["cqg_connection_status"] == 1) {
                    return response()->json([
                        'status' => true,

                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Challenges can only be activated during Globex market hours",
                        // 'message' => "CQG Official Maintnenace Windows are: Weekdays from 4-4:30pm US Chicago time Weekends from Fri 6pm to Sun 9am US Chicago time",
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'No Data response form go lang',
                    'resData' => $getData,

                ], 400);
            }
        }

    }
}
