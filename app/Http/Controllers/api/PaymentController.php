<?php

namespace App\Http\Controllers\api;

use App\Events\NewUserRegistration;
use App\Http\Controllers\Controller;
use App\Mail\ChallengePurchase;
use App\Models\CardChallenge;
use App\Models\ChallengeMarket;
use App\Models\CouponCode;
use App\Models\MarketData;
use App\Models\MonthlyPay;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\PackagePurchaseMarket;
use App\Models\SoftSetting;
use App\Models\SubscribeLog;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserMarketData;
use App\Notifications\Challenge;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use stdClass;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function stripe(Request $request)
    {
        Log::info(['stripe' => $request->all()]);

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException$e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'customer.subscription.created':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);

                $stripePayMentId = $paymentIntent->id;

                $verifyID = $this->checkStripId($stripePayMentId);

                Log::info(['Store Data' => $verifyID]);

                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }

    public function checkStripId($stripID)
    {
        $stripeConfigKye = config('app.stripe_secret_kye');
        $currentDate = Carbon::now();
        // public function checkStripId() {
        $testId = $stripID;
        // $testId = "sub_1MGqHWG8eX1XeOGiEIFeQ2Bl";
        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );
        $data = $stripe->subscriptions->retrieve(
            $testId,
            []
        );

        $customer = $stripe->customers->retrieve(
            $data->customer,
            []
        );

        $product = $stripe->products->retrieve(
            $data->plan->product,
            []
        );

        $dataInvoice = $stripe->invoices->retrieve(
            $data->latest_invoice,
            []
        );

        $datacharge = $stripe->charges->retrieve(
            $dataInvoice->charge,
            []
        );

        $datatran = $stripe->balanceTransactions->retrieve(
            $datacharge->balance_transaction,
            []
        );
        $stipeFee = $datatran->fee;

        $challangeID = $product->metadata->challenge_uuid;

        if (empty($challangeID)) {

            // $value = Redirect::route('platform.payment.check', $testId);

            $this->checkMonthlyPaymentSubscription($testId);

            return $data;
        }

        $start_date = $currentDate->format('Y-m-d');
        $end_date = $currentDate->add(1, 'month')->format('Y-m-d');

        // $start_date = date("Y-m-d", $data->current_period_start);
        // $end_date = date("Y-m-d", $data->current_period_end);
        $customerEmail = $customer->email;

        $user = User::with('userDetail')->where('email', $customerEmail)->first();

        if (!empty($user)) {
            $userDetail = UserDetail::findOrFail($user->userDetail->id);

            if (!empty($userDetail)) {

                $packagePurchaseAccountDetail = DB::transaction(function () use ($userDetail, $start_date, $end_date, $user, $challangeID, $stipeFee) {

                    $userDetail = UserDetail::findOrFail($user->userDetail->id);
                    $userDetail->start_date = $start_date;
                    $userDetail->end_date = $end_date;
                    $userDetail->status = 1;
                    $userDetail->save();
                    // Notification::send($user, new Challenge());

                    $challenge = CardChallenge::where('uuid', $challangeID)->first();
                    $packageDetail = array(

                        "card_challenge_id" => $challenge->id,
                        "user_id" => $user->id,
                        "account_status" => "In-Progress",
                        "account_activation_status" => 0,
                        "stripe_amount" => (double) $stipeFee / 100,
                        "start_date" => $start_date,
                        "end_date" => $end_date,
                    );

                    $packagePurchase = PackagePurchaseAccountDetail::create($packageDetail);

                    $mailObject = new stdClass;
                    $mailObject->name = $userDetail->first_name . ' ' . $userDetail->last_name;
                    $mailObject->challangeName = $challenge->title;
                    $mailObject->date = $start_date;
                    $mailObject->price = $challenge->price;
                    try {
                        Mail::to($user->email)->send(new ChallengePurchase($mailObject));
                    } catch (Exception $e) {
                        $e->getMessage();
                    }

                    $notification = $this->notification($user);

                    return $packagePurchase;
                });
                $testData = $this->getGoUserRegResponse($customerEmail, $challangeID, $testId, $packagePurchaseAccountDetail);
                return response()->json([
                    'data get' => $testData,

                ]);
                return $testData;

            }
        }

        return $data;
    }

    public function checkStripIdTest()
    {
        $stripeConfigKye = config('app.stripe_secret_kye');

        $currentDate = Carbon::now();
        // public function checkStripId() {

        $testId = "sub_1MUCtFG8eX1XeOGi3EK7YcDm";
        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );

        $customer = $stripe->customers->retrieve(
            'cus_NF4avmBQz7R1Pv',
            []
        );

        $stripePaymentMethod = $stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'number' => 4242424242424242,
                'exp_month' => 05,
                'exp_year' => 2052,
                'cvc' => 123,
            ],
        ]);

        $attachPaymethod = $stripe->paymentMethods->attach(
            $stripePaymentMethod->id,
            ['customer' => 'cus_NF4avmBQz7R1Pv']
        );

        $updateCustome = $stripe->customers->update(
            'cus_NF4avmBQz7R1Pv',
            ['invoice_settings' => ['default_payment_method' => $attachPaymethod->id]]
        );

        $ncustomer = $stripe->customers->retrieve(
            'cus_NF4avmBQz7R1Pv',
            []
        );

        $paymentMethod = $stripe->paymentMethods->retrieve(
            'pm_1MTrk7G8eX1XeOGiCfUGW4z7',
            []
        );

        $data = $stripe->subscriptions->retrieve(
            $testId,
            []
        );

        $dataInvoice = $stripe->invoices->retrieve(
            $data->latest_invoice,
            []
        );

        $datacharge = $stripe->charges->retrieve(
            $dataInvoice->charge,
            []
        );

        $datatran = $stripe->balanceTransactions->retrieve(
            $datacharge->balance_transaction,
            []
        );
        $stipeFee = $datatran->fee;

        $customer = $stripe->customers->retrieve(
            $data->customer,
            []
        );
        $product = $stripe->products->retrieve(
            $data->plan->product,
            []
        );

        $challangeID = $product->metadata->challenge_uuid;

        // $start_date = date("Y-m-d", $data->current_period_start);
        // $end_date = date("Y-m-d", $data->current_period_end);

        $start_date = $currentDate->format('Y-m-d');
        $end_date = $currentDate->add(1, 'month')->format('Y-m-d');
        $customerEmail = $customer->email;

        $user = User::with('userDetail')->where('email', $customerEmail)->first();

        if (!empty($user)) {
            $userDetail = UserDetail::findOrFail($user->userDetail->id);

            if (!empty($userDetail)) {

                $packagePurchaseAccountDetail = DB::transaction(function () use ($userDetail, $start_date, $end_date, $user, $challangeID) {

                    $userDetail = UserDetail::findOrFail($user->userDetail->id);
                    $userDetail->start_date = $start_date;
                    $userDetail->end_date = $end_date;
                    $userDetail->status = 1;
                    $userDetail->save();
                    Notification::send($user, new Challenge());
                    $challenge = CardChallenge::where('uuid', $challangeID)->first();
                    $packageDetail = array(

                        "card_challenge_id" => $challenge->id,
                        "user_id" => $user->id,
                        "account_status" => "In-Progress",
                        "account_activation_status" => 0,
                    );

                    $packagePurchase = PackagePurchaseAccountDetail::create($packageDetail);

                    $mailObject = new stdClass;
                    $mailObject->name = $userDetail->first_name . ' ' . $userDetail->last_name;
                    $mailObject->challangeName = $challenge->title;
                    try {
                        Mail::to($user->email)->send(new ChallengePurchase($mailObject));
                    } catch (Exception $e) {
                        $e->getMessage();
                    }

                    $notification = $this->notification($user);
                    return $packagePurchase;
                });

                $testData = $this->getGoUserRegResponse($customerEmail, $challangeID, $testId, $packagePurchaseAccountDetail);
                return response()->json([
                    'data get' => $testData,

                ]);

            }

        } else {
        }
    }

    public function customerCreateSubscription(Request $request)
    {
        $stripeConfigKye = config('app.stripe_secret_kye');

        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );
        $customer = $stripe->customers->create([
            'email' => $request->email,
            'name' => $request->name,
            'payment_method' => $request->payment_method,
            'invoice_settings' => ['default_payment_method' => $request->payment_method],
        ]);

        if (!empty($customer)) {

            if (!empty($request->coupon_id)) {

                try {
                    $stripe->coupons->retrieve($request->coupon_id, []);
                    $subscription = $stripe->subscriptions->create([
                        'customer' => $customer->id,
                        'items' => [
                            ['price' => $request->product_price_id],
                        ],
                        'coupon' => $request->coupon_id,
                    ]);

                } catch (Exception $e) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Coupon is not valid',
                    ], 401);
                }

            } else {
                $subscription = $stripe->subscriptions->create([
                    'customer' => $customer->id,
                    'items' => [
                        ['price' => $request->product_price_id],
                    ],
                ]);
            }

            $cardSource = $stripe->customers->createSource(
                $customer->id,
                ['source' => $request->token]
            );

            $user = Auth::guard('api')->user();
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            $userDetail->stripe_pay_token = $cardSource->id;
            $userDetail->save();

            return response()->json([
                'status' => 200,
                'data' => $subscription,
            ], 200);

        } else {
            return response()->json([
                'status' => 404,
                'message' => "Customer not created",
            ], 404);
        }
        return $customer;
    }

    public function getGodata(Request $request)
    {
        return response()->json([
            'new_user' => ["request_id" => 2, "success" => true, "reason" => "", "new_trader" => ["id" => "G1131745", "name" => "TTest51989"], "new_account" => ["id" => 1990460, "name" => "py551y82"], "new_customer_id" => 1721818],
            'AmpId' => 1727542,
        ]);
    }

    public function getGoUserRegResponse($email, $challangeID, $testId, $packagePurchaseAccountDetail)
    {
        $go_lang_url = config('app.go_lang_url');
        $go_lang_url = $go_lang_url . "futures/user/registration";
        $cqg_market = config('app.cqg_market');

        $challenge = CardChallenge::where('uuid', $challangeID)->first();
        $customerEmail = $email;
        $user = User::with('userDetail')->where('email', $customerEmail)->first();
        $stripeConfigKye = config('app.stripe_secret_kye');
        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );
        $data = $stripe->subscriptions->retrieve(
            $testId,
            []
        );

        $dataInvoice = $stripe->invoices->retrieve(
            $data->latest_invoice,
            []
        );

        $datacharge = $stripe->charges->retrieve(
            $dataInvoice->charge,
            []
        );

        $datatran = $stripe->balanceTransactions->retrieve(
            $datacharge->balance_transaction,
            []
        );
        $stipeFee = $datatran->fee;

        // dd($data->items->data[0]['id']);
        $stripeId = $data->items->data[0]['id'];
        $subscription_Id = $data->id;
        $payment_status = $data->status;
        $getPrice = $data->items->data[0]['price']->unit_amount;
        $packagePrice = (double) $getPrice / 100;

        if ((!empty($challenge)) && (!empty($user))) {
            $marketData = MarketData::find($challenge->market_data_id)->first();

            $OldAcc = array(
                'firstName' => $user->userDetail?->first_name,
                'lastName' => $user->userDetail?->last_name,
                'email' => $user->email,
                'country' => 'United States of America',
                'state' => 'New York',
                // 'SalesSeriesId' => 29160,
                'risk_account_clone_id' => (string) $challenge->clone_id,
                'starting_balance' => (int) $challenge->buying_power,
                'cqg_market' => $cqg_market,
                'market_data_feed_id' => (string) $marketData->api_id,

                'accountId' => (string) $user->account_id_number,
                'ampid' => (string) $user->ampid,
                'traderid' => (string) $user->traderid,
            );

            if (!empty($user->account_id_number)) {

                $response = Http::post($go_lang_url, [

                    'firstName' => $user->userDetail?->first_name,
                    'lastName' => $user->userDetail?->last_name,
                    'email' => $user->email,
                    'country' => 'United States of America',
                    'state' => 'New York',
                    // 'SalesSeriesId' => 29160,
                    'risk_account_clone_id' => (string) $challenge->clone_id,
                    'starting_balance' => (int) $challenge->buying_power,
                    'cqg_market' => $cqg_market,
                    'market_data_feed_id' => (string) $marketData->api_id,

                    'accountId' => (string) $user->account_id_number,
                    'ampid' => (string) $user->ampid,
                    'traderid' => (string) $user->traderid,
                    "accountSize" => 50000,
                ]);
            } else {
                $response = Http::post($go_lang_url, [

                    'firstName' => $user->userDetail?->first_name,
                    'lastName' => $user->userDetail?->last_name,
                    'email' => $user->email,
                    'country' => 'United States of America',
                    'state' => 'New York',
                    // 'SalesSeriesId' => 29160,
                    'risk_account_clone_id' => (string) $challenge->clone_id,
                    'starting_balance' => (int) $challenge->buying_power,
                    'cqg_market' => $cqg_market,
                    'market_data_feed_id' => (string) $marketData->api_id,
                    "accountSize" => 50000,

                ]);

                $newAcc = array(
                    'firstName' => $user->userDetail?->first_name,
                    'lastName' => $user->userDetail?->last_name,
                    'email' => $user->email,
                    'country' => 'United States of America',
                    'state' => 'New York',
                    // 'SalesSeriesId' => 29160,
                    'risk_account_clone_id' => (string) $challenge->clone_id,
                    'starting_balance' => (int) $challenge->buying_power,
                    'cqg_market' => $cqg_market,
                    'market_data_feed_id' => (string) $marketData->api_id,

                    'accountId' => (string) $user->account_id_number,
                    'ampid' => (string) $user->ampid,
                    'traderid' => (string) $user->traderid,
                );

            }

            $getData = $response->collect();

            // dd($getData);

            if (($response->failed() == false) && !empty($getData)) {

                $status = DB::transaction(function () use ($stipeFee, $getData, $user, $stripeId, $subscription_Id, $payment_status, $challenge, $packagePrice, $packagePurchaseAccountDetail) {
                    $marketData = MarketData::first();

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

                    $user->ampid = $ampId;
                    $user->account_id_number = $accountID;
                    $user->account_name = $accountName;
                    $user->traderid = $tradeId;
                    $user->market_data_id = $marketData->id;

                    $user->save();

                    $inserData = array(
                        "user_id" => $user->id,
                        "stripe_id" => $stripeId,
                        "stripe_subscription_id" => $subscription_Id,
                        "payment_status" => $payment_status,
                        "response" => $getData,
                        "package_purchase_account_detail_id" => $packagePurchaseAccountDetail->id,
                    );
                    SubscribeLog::create($inserData);

                    $packageDetail = array(
                        "request_id" => $request_id,
                        "amp_id" => $ampId,
                        "card_challenge_id" => $challenge->id,
                        "user_id" => $user->id,
                        "trader_id" => $tradeId,
                        "trader_name" => $tradeName,
                        "account_activation_status" => $account_activation_status,
                        "account_id" => $accountID,
                        "account_name" => $accountName,
                        "new_customer_id" => $customerid,
                        "package_price" => $packagePrice,
                        "account_status" => "In-Progress",
                        "stripe_amount" => (double) $stipeFee / 100,
                    );

                    $packagePurchaseAccountDetail->update($packageDetail);

                    // New code for extra market data active
                    $extraMarketData = ChallengeMarket::where('card_challenge_id', $challenge->id)->get();
                    Log::info(['extraMarketData' => $extraMarketData]);
                    if ($extraMarketData->isNotEmpty() && $extraMarketData->count() > 0) {
                        foreach ($extraMarketData as $marketkey => $marketDataValue) {
                            $packagePurchaseMarket[$marketkey] = array(
                                "uuid" => (string) Str::uuid(),
                                "package_purchase_account_detail_id" => $packagePurchaseAccountDetail->id,
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

                        UserMarketData::where('user_id', $user->id)->delete();
                        PackagePurchaseMarket::insert($packagePurchaseMarket);
                        UserMarketData::insert($usrExtraMarket);
                    }

                    // New code for extra market data active

                    return $inserData;
                });

                return $status;
            } else {
                //inser data to log table

                $inserData = array(
                    "user_id" => $user->id,
                    "stripe_id" => $stripeId,
                    "stripe_subscription_id" => $subscription_Id,
                    "payment_status" => $payment_status,
                    "response" => $getData,
                    "package_purchase_account_detail_id" => $packagePurchaseAccountDetail->id,
                );
                SubscribeLog::create($inserData);

                return $getData;
            }
        } else {
            //insert fail data
            $inserData = array(
                "user_id" => $user->id,
                "stripe_id" => $stripeId,
                "stripe_subscription_id" => $subscription_Id,
                "payment_status" => $payment_status,
                "response" => null,
                "package_purchase_account_detail_id" => $packagePurchaseAccountDetail->id,
            );
            SubscribeLog::create($inserData);
            return $inserData;
        }
    }

    public function marketDataPurchaseStripe()
    {
        return response()->json([
            'status' => 200,
            'message' => "Market Data api call",
        ], 200);
    }

    public function checkMonthlyPaymentSubscription($stripeId)
    {
        $stripeConfigKye = config('app.stripe_secret_kye');

        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );

        $dataSubs = $stripe->subscriptions->retrieve(
            $stripeId,
            []
        );

        $dataInvoice = $stripe->invoices->retrieve(
            $dataSubs->latest_invoice,
            []
        );

        $datacharge = $stripe->charges->retrieve(
            $dataInvoice->charge,
            []
        );

        $datatran = $stripe->balanceTransactions->retrieve(
            $datacharge->balance_transaction,
            []
        );
        $stipeFee = $datatran->fee;

        if (!empty($dataSubs)) {

            $theValue = DB::transaction(function () use ($dataSubs, $stipeFee) {
                $subsId = $dataSubs->id;
                $monthlyPayment = MonthlyPay::where("stripe_payment_id", $subsId)->get()->first();
                $monthlyPayment->status = 1;
                $monthlyPayment->stripe_amount = (double) $stipeFee / 100;
                $monthlyPayment->save();

                $monthlyPayDue = MonthlyPay::where("status", 0)->get();
                if ($monthlyPayDue->count() <= 0) {
                    $softSetting = SoftSetting::findOrFail(1);
                    $softSetting->status = 1;
                    $softSetting->save();
                    return true;
                } else {
                    return true;
                }

            });

        }

        if ($theValue == true) {
            return true;
        } else {
            return false;
        }

    }

    public function upDateCouponApply($couponCode)
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail->account_id_number)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $couponStatus = CouponCode::where('coupon_id', $couponCode)->first();

            if (!empty($couponStatus)) {

                $couponStatus->increment('total_apply');

                return response()->json([
                    'status' => 200,
                    'message' => "Coupon Apply Update",
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "Coupon Apply Not Update",
                ], 400);
            }
        }

    }

    public function notification($user)
    {

        $data['image'] = "";
        $data['head_title'] = "purchase of challenge";
        $data['message'] = "We are delighted to inform you that your purchase of our challenge plan has been successful! Thank you for choosing Trading School.";
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
