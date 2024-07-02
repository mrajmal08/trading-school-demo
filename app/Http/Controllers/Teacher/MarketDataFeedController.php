<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CardChallenge;
use App\Models\CouponCode;
use App\Models\MarketData;
use App\Models\MarketDataPurchaseDetail;
use App\Models\MarketDataStripe;
use App\Models\MonthlyPay;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\User;
use App\Models\UserDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class MarketDataFeedController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $marketDataList = MarketData::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })->paginate(10);
        return view('pages.teacher.market-data-feed.market-data-feed-list', compact('marketDataList'));
    }

    public function edit(MarketData $marketData)
    {
        return view('pages.teacher.market-data-feed.market-data-feed-edit', compact('marketData'));
    }

    public function update(Request $request, MarketData $marketData)
    {

        $marketDataId = MarketDataStripe::where('market_data_id', $marketData->id)->first();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'original_price' => 'required',
            'buffer_price' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $data = $validator->validated();

        $stripe = new StripeClient(
            env('STRIPE_SECRET_KEY')
        );

        /*===================create product==============*/
        if (empty($marketDataId)) {
            // Create a product over stripe
            $product_detail = $stripe->products->create([
                'name' => $request->name,
            ]);
            // create prices for the product
            $productPrice = $stripe->prices->create([
                'unit_amount' => ($request->price * 100) * 2,
                'currency' => 'usd',
                'recurring' => ['interval' => 'month'],
                'product' => $product_detail->id,
            ]);
            // default prices for the product
            $stripeUpdatePrice = $stripe->products->update(
                $product_detail->id,
                ['default_price' => $productPrice->id]
            );

            MarketDataStripe::create([
                "market_data_id" => $marketData->id,
                "stripe_product_id" => $product_detail->id,
                "stripe_product_price_id" => $productPrice->id,
            ]);
        }

        /*=====================update price=================*/
        if (!empty($marketDataId) && $marketData->price != $request->price) {

            $stripeCreatePrice = $stripe->prices->create([
                'unit_amount' => ($request->price * 100) * 2,
                'currency' => 'usd',
                'product' => $marketDataId->stripe_product_id,
            ]);

            $stripeUpdatePrice = $stripe->products->update(
                $marketDataId->stripe_product_id,
                ['default_price' => $stripeCreatePrice->id]
            );

            $marketData->marketDataStripe->stripe_product_price_id = $stripeUpdatePrice->default_price;
            $marketData->marketDataStripe->save();
        }

        $row = $marketData->update($data);

        if ($row) {
            return Redirect::route('teacher.market-data-feed.index')->with(['success' => 'Market data feed update successfully']);
        }
    }

    public function subscription(Request $request)
    {

        // \Stripe\Stripe::setApiKey('pk_test_51MGlcQG8eX1XeOGikpRRhaZwrvhI1z2gCcsET1MbPmm1C79b6tl4nD3zIAVN71tO2YpNp4M86HMF4N8Mn91qc8rz00tjZ2392K');
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_cc687bb4f8541d9c1cb6d2095b5af2cc478a3527732b3d5418b5a77feb77c420';

        $payload = @file_get_contents('php://input');

        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException$e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException$e) {
            // Invalid signature
            // dd($e);
            // http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'charge.refunded':
                $charge = $event->data->object;
                $this->chargeRefunded($charge->charge);
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $this->customerSubscriptionDeleted($subscription->id);
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

    }

    // public function chargeRefunded(Request $request)
    public function chargeRefunded($chargeId)
    {
        $stripeConfigKye = config('app.stripe_secret_kye');
        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );

        // $refundData = $stripe->charges->retrieve(
        //     'ch_3MU9gTG8eX1XeOGi1YzPN6AH',
        //     []
        // );

        $refundData = $stripe->charges->retrieve(
            $chargeId,
            []
        );

        $invoice = $stripe->invoices->retrieve(
            $refundData->invoice,
            []
        );

        $productDetails = $stripe->products->retrieve(
            $invoice['lines']['data']['0']['plan']['product'],
            []
        );

        if (!empty($productDetails->metadata['challenge_uuid'])) {
            $userId = User::where('email', $invoice->customer_email)->first();
            if ($userId) {
                $result = UserDetail::where('user_id', $userId->id)->update(['status' => '0']);
                if ($result) {
                    $go_lang_url = config('app.go_lang_url');
                    $go_lang_url = $go_lang_url . "futures/user/all-account/disable";
                    $response = Http::post($go_lang_url, [
                        "traderId" => $userId->traderid,
                    ]);
                    $getData = $response->collect();
                    if ($getData->res == true) {
                        $packagePurchaseDetail = PackagePurchaseAccountDetail::orderBy("id", 'desc')->where('user_id', $userId->id)->first();
                        $packagePurchaseDetail->account_status = "cancel";
                        $packagePurchaseDetail->save();

                        return response()->json([
                            'status' => 201,
                            'message' => 'User status successfully',
                        ], 201);
                    } else {
                        $packagePurchaseDetail = PackagePurchaseAccountDetail::orderBy("id", 'desc')->where('user_id', $userId->id)->first();
                        $packagePurchaseDetail->account_status = "must_deactive";
                        $packagePurchaseDetail->save();
                        return response()->json([
                            'status' => 201,
                            'message' => 'Trade Account deactivate unsuccessful',
                        ], 201);
                    }

                    return response()->json([
                        'status' => 201,
                        'message' => 'User status successfully',
                    ], 201);
                } else {
                    return response()->json([
                        'status' => 401,
                        'message' => 'User status not updated',
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'User not found',
                ], 400);
            }
        } elseif ($productDetails->metadata['type'] == 'market') {
            $userId = User::where('email', $invoice->customer_email)->first();
            $go_lang_url = config('app.go_lang_url');
            $go_lang_url = $go_lang_url . "futures/feeds/deactivate";

            $marketDetail = MarketData::find($userId->market_data_id);

            $response = Http::post($go_lang_url, [
                "cqgTraderId" => $userId->traderid,
                "cqgFeedId" => $marketDetail->api_id,
            ]);
            $getData = $response->collect();

            if ($getData->res == true) {

                $packagePurchaseDetail = PackagePurchaseAccountDetail::orderBy("id", 'desc')->where('user_id', $userId->id)->first();
                $challengesID = CardChallenge::find($packagePurchaseDetail->card_challenge_id);
                $userId->market_data_id = $challengesID->market_data_id;
                $userId->save();

                return response()->json([
                    'status' => 401,
                    'message' => 'Market data deactivation success',
                ], 400);

            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Market data deactivation fail',
                ], 400);

            }

        } else {

            $stripeSystemPaymentUpdate = MonthlyPay::where('stripe_payment_id', $refundData->payment_intent)->update(['status' => '0']);
            if ($stripeSystemPaymentUpdate) {
                return response()->json([
                    'status' => 201,
                    'message' => 'Stripe system payment update successfully',
                ], 201);
            }
        }

    }

    // public function customerSubscriptionDeleted(Request $request)
    public function customerSubscriptionDeleted($subscriptionId)
    {
        $stripeConfigKye = config('app.stripe_secret_kye');
        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );

        // $subscriptionDetail = $stripe->subscriptions->retrieve(
        //     'sub_1MUCtFG8eX1XeOGi3EK7YcDm',
        //     []
        // );

        $subscriptionDetail = $stripe->subscriptions->retrieve(
            $subscriptionId,
            []
        );

        $stripePaymentId = $stripe->invoices->retrieve(
            $subscriptionDetail->latest_invoice,
            []
        );

        $productDetails = $stripe->products->retrieve(
            $subscriptionDetail['plan']->product,
            []
        );

        $customerDetails = $stripe->customers->retrieve(
            $subscriptionDetail->customer,
            []
        );

        if (!empty($productDetails->metadata['challenge_uuid'])) {
            $userId = User::where('email', $customerDetails->email)->first();
            if ($userId) {
                $result = UserDetail::where('user_id', $userId->id)->update(['status' => '0']);
                if ($result) {
                    return response()->json([
                        'status' => 201,
                        'message' => 'User status successfully',
                    ], 201);
                } else {
                    return response()->json([
                        'status' => 401,
                        'message' => 'User status not updated',
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'User not found',
                ], 400);
            }
        } elseif ($productDetails->metadata['type'] == 'market') {
            dd("go lang api will be call");
        } else {

            $stripeSystemPaymentUpdate = MonthlyPay::where('stripe_payment_id', $stripePaymentId->payment_intent)->update(['status' => '0']);
            if ($stripeSystemPaymentUpdate) {
                return response()->json([
                    'status' => 201,
                    'message' => 'Stripe system payment update successfully',
                ], 201);
            }
        }

    }

    public function findCoupon(Request $request)
    {
        $couponCode = CouponCode::where('coupon_id', $request->coupon_code)->first();

        if ($couponCode->max_use == 'forever' && $couponCode->total_number > 0 ) {
            if (($couponCode->total_apply >= $couponCode->total_number) || $couponCode->status != 1) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Coupon is not valid',
                ], 401);
            }
        }

        if ($couponCode->max_use == 'once' ) {
            if (($couponCode->total_apply >= $couponCode->total_number) || $couponCode->status != 1) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Coupon is not valid',
                ], 401);
            }
        }

        $stripeConfigKye = config('app.stripe_secret_kye');
        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );

        try {
            $result = $stripe->coupons->retrieve($request->coupon_code, []);
            return response()->json([
                'status' => 201,
                'message' => 'Coupon code found',
                'data' => $result,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Coupon is not valid',
            ], 401);
        }
    }

    public function marketDataPurchaseList(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $country = $request->query('country');

        $dates = explode("-", $request->query('dates'));
        $startDate = date("Y-m-d", strtotime($dates['0']));
        $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');

        $marketDataDetails = MarketDataPurchaseDetail::with('user.userDetail', 'marketData')
        ->when($status, function($query) use ($status) {
            return $query->where('account_activation_status', $status === 'active' ? 1 : 0);
        })
        ->when($search, function ($query) use ($search) {
            return $query->where('account_name', 'like', "%$search%");
        })

        ->when($search, function ($query) use ($search) {
            return $query->orwhereHas('user.userDetail', function ($query) use ($search) {
                $query->WhereRaw("concat(first_name, ' ', last_name) like '%" .$search. "%' ");
            });
        })

        ->when($search, function ($query) use ($search) {
            return $query->orwhereHas('user', function ($query) use ($search) {
                $query->where('email', 'like', "%$search%");
            });
        })

        ->when(!empty($endDate), function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->orderBy('id', 'DESC')->paginate(10);
        $userList = User::with('userDetail')->get();
        return view('pages.teacher.market-data-feed.market-data-purchase-list', compact(['marketDataDetails', 'userList', 'status', 'search']));
    }

    public function marketDataPurchaselog(Request $request){
        return MarketDataPurchaseDetail::where('user_id',$request->userId)->orderBy('id', 'DESC')->first();
    }

    public function reProcessMarketData(MarketDataPurchaseDetail $marketDataPurchaseDetail)
    {
        $go_lang_url = config('app.go_lang_url');
        $go_lang_url = $go_lang_url . "futures/feeds/activate";

        $marketData = MarketData::findOrFail($marketDataPurchaseDetail->market_data_id);

        $response = Http::post($go_lang_url, [
            'cqgTraderId' => (string) $marketDataPurchaseDetail->trader_id,
            'ampId' => (string) $marketDataPurchaseDetail->amp_id,
            'feedId' => (string) $marketData->api_id,
        ]);

        $status = $response->object();
        if ($status === true) {
            DB::transaction(function () use ($marketDataPurchaseDetail, $response) {
                $userDetail = User::where('id', $marketDataPurchaseDetail->user_id)->first();

                $userDetail->market_data_id = $marketDataPurchaseDetail->market_data_id;
                $userDetail->save();

                $marketDataPurchaseDetail->account_activation_status = 1;
                $marketDataPurchaseDetail->log = $response;
                $marketDataPurchaseDetail->save();
            });
            return Redirect::route('teacher.marketDataPurchaseList')->with(['success' => 'Market data feed update successfully']);
        } else {

            $marketDataPurchaseDetail->account_activation_status = 0;
            $marketDataPurchaseDetail->log = $response;
            $marketDataPurchaseDetail->save();
            return Redirect::route('teacher.marketDataPurchaseList')->with(['error' => 'Market data not updated']);
        }
    }
}
