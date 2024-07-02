<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\MonthlyPay;
use App\Models\PlatfromPayment;
use App\Models\SoftSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PlatformPaymentController extends Controller
{
    public function index(Request $request)
    {
        $dates = $request->query('dates');
        $platfromPayment = PlatfromPayment::when($dates, function ($query) use ($dates) {
            $dates = explode("-", $dates);
            $startDate = date("Y-m-d", strtotime($dates['0']));
            $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })->orderBy('id', 'DESC')->paginate(12);

        return view('pages.teacher.platform-payment', compact('platfromPayment'));
    }

    public function monthlyPay(Request $request)
    {
        if ($request->query('dates')) {
            $date = $request->query('dates');
            $splitDate = \explode("-", $date);
            $startData = $splitDate[0];
            $endData = $splitDate[1];
        } else {
        }

        $startData = Carbon::now()->startOfMonth();
        $endData = Carbon::now()->endOfMonth();
        $currentMonthPay = MonthlyPay::whereBetween('created_at', [$startData, $endData])->get()->first();

        $payments = MonthlyPay::paginate(8);

        return view('pages.teacher.payment', compact('payments', 'currentMonthPay'));

    }

    public function paymentMonthly(Request $request)
    {
        $currentUser = Auth::guard('admin')->user();
        $validator = Validator::make($request->all(), [
            'card_number' => 'required',
            'expiry_date' => 'required',
            'cvc' => 'required',
            'monthly_uuid' => 'required',
        ]);

        if ($validator->fails()) {

            return Redirect::back()->with(['error' => "Error in Card Information"]);
        } else {
            $stripeConfigKye = config('app.stripe_secret_kye');
            $stripe = new \Stripe\StripeClient(
                $stripeConfigKye
            );

            $expirydate = explode("/", $request->expiry_date);
            $expiryMonth = $expirydate[0];
            $expiryYear = "20" . $expirydate[1];

            $stripeToken = $stripe->tokens->create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $expiryMonth,
                    'exp_year' => (int) $expiryYear,
                    'cvc' => $request->cvc,
                ],
            ]);

            if (!empty($stripeToken)) {

                $monthlyPayment = MonthlyPay::where('uuid', $request->monthly_uuid)->first();

                $customer = $stripe->customers->search([
                    'query' => 'email:\'' . $currentUser->email . '\'',
                ]);

                $stripePorductDetail = $stripe->products->retrieve(
                    $monthlyPayment->stripe_product_id,
                    []
                );

                if (empty($customer->data)) {
                    $customer = $stripe->customers->create([
                        'email' => $currentUser->email,
                        'name' => $currentUser->name,
                        'source' => $stripeToken->id,
                    ]);

                    $customerId = $customer->id;
                } else {
                    $customerId = $customer->data[0]["id"];

                }

                //create subscrib charage
                if (!empty($request->isSubscribed)) {

                    $monthlystripePorductDetail = $stripe->products->retrieve(
                        $monthlyPayment->stripe_monthly_product_id,
                        []
                    );

                    $subscription = $stripe->subscriptions->create([
                        'customer' => $customerId,
                        'items' => [
                            ['price' => $monthlystripePorductDetail->default_price],
                        ],
                    ]);

                    $monthlyPayment->pay_date = Carbon::now();
                    $monthlyPayment->stripe_payment_id = $subscription->id;
                    $monthlyPayment->save();
                    $value = $this->checkMonthlyPaymentSubscription($subscription->id);

                    if ($value == true) {
                        return Redirect::back()->with(['success' => "Payment success"]);
                    } else {
                        return Redirect::back()->with(['error' => "Payment not success"]);
                    }
                } else {
                    //create one time charage
                    $monthlypay = config('app.monthly_payment');

                    $chargeId = $stripe->charges->create([
                        'amount' => (double) $monthlypay * 100,
                        'currency' => 'usd',
                        'customer' => $customerId,
                    ]);

                    $chargesripeID = $chargeId->id;
                    $chargeStatus = $chargeId->status;

                    if ($chargeStatus == "succeeded") {

                        $monthlyPayment->pay_date = Carbon::now();
                        $monthlyPayment->stripe_payment_id = $chargesripeID;
                        $monthlyPayment->save();

                        $value = $this->checkMonthlyPayment($chargesripeID);

                        if ($value == true) {
                            return Redirect::back()->with(['success' => "Payment success"]);
                        } else {
                            return Redirect::back()->with(['error' => "Payment not success"]);
                        }
                    }
                }

            } else {
                return Redirect::back()->with(['error' => "Card is not valid"]);
            }
        }

    }

    public function checkMonthlyPayment($stripeId)
    {

        $stripe = new \Stripe\StripeClient(
            env("STRIPE_SECRET_KEY")
        );

        $chargesSubs = $stripe->charges->retrieve(
            $stripeId,
            []
        );

        $datatran = $stripe->balanceTransactions->retrieve(
            $chargesSubs->balance_transaction,
            []
        );
        $stipeFee = $datatran->fee;

        if (!empty($chargesSubs)) {

            $theValue = DB::transaction(function () use ($chargesSubs, $stipeFee) {
                $subsId = $chargesSubs->id;

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

    public function checkMonthlyPaymentSubscription($stripeId)
    {

        $stripe = new \Stripe\StripeClient(
            env("STRIPE_SECRET_KEY")
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

    public function recievePlatformPayment(PlatfromPayment $platfromPayment)
    {

        $platfromPayment->status = 1;
        $platfromPayment->save();

        return Redirect::back()->with(['success' => 'Receive Successful']);
    }

}
