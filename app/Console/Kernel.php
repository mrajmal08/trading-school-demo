<?php

namespace App\Console;

// use App\Models\CardChallenge;
// use App\Models\CardHead;
// use App\Models\CardHeadSubTitle;
// use App\Models\CardHeadTitle;
// use App\Models\CardSubHeadTitle;
// use Illuminate\Support\Facades\Http;

use App\Mail\SubscriptionCancel;
use App\Models\MarketDataPurchaseDetail;
use App\Models\MonthlyPay;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\PlatfromPayment;
use App\Models\StripeMonthlyProduct;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;
use stdClass;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {

            // condetion check Data

            // $startData = Carbon::now()->startOfMonth();
            // $endData = Carbon::now()->endOfMonth();

            // $mothlyPay = MonthlyPay::whereDate('created_at', [$startData, $endData])->get();

            // if (empty($mothlyPay)) {

            // }

            // condetion check Data

            $userDetail = UserDetail::whereDate('end_date', '<', Carbon::today()->toDateString())->get();
            foreach ($userDetail as $key => $uvalue) {
                $uvalue->status = 0;
                $uvalue->save();
            }

            $monthlyPayment = config('app.monthly_payment');

            // //dev

            // MonthlyPay::create([
            //     'amount' => $monthlyPayment,
            //     'stripe_product_id' => "prod_NCOHjwN2Qg7sSN",
            //     'stripe_monthly_product_id' => "prod_NCr8w3kk9YAlJD",
            //     'status' => 0,
            // ]);

            // Tradin school Demo
            // MonthlyPay::create([
            //     'amount' => $monthlyPayment,
            //     'stripe_product_id' => "prod_NCOHjwN2Qg7sSN",
            //     'stripe_monthly_product_id' => "prod_NCr8w3kk9YAlJD",
            //     'status' => 0,
            // ]);

            //jigsow

            $stripeMonthlyProdcut = StripeMonthlyProduct::first();
            MonthlyPay::create([
                'amount' => $monthlyPayment,
                'stripe_product_id' => $stripeMonthlyProdcut->stripe_product_id,
                'stripe_monthly_product_id' => $stripeMonthlyProdcut->stripe_monthly_product_id,
                'status' => 0,
            ]);

            //protrader

            // MonthlyPay::create([
            //     'amount' => $monthlyPayment,
            //     'stripe_product_id' => "prod_NewbYjrlMpANcA",
            //     'stripe_monthly_product_id' => "prod_NewbXrwqygNZ2a",
            //     'status' => 0,
            // ]);

            // $startData = Carbon::now()->startOfMonth();
            // $endData = Carbon::now()->endOfMonth();
            // $challangesTotalPrice = PackagePurchaseAccountDetail::with('cardChallenge')->whereBetween('created_at', [$startData, $endData])->get()->pluck('cardChallenge.price')->sum();
            // $marketDataTotalPrice = MarketDataPurchaseDetail::whereBetween('created_at', [$startData, $endData])->get()->sum('package_price');
            // $totalPaymentget = (double) $challangesTotalPrice + (double) $marketDataTotalPrice;

            // $packageStripeFee = PackagePurchaseAccountDetail::whereBetween('created_at', [$startData, $endData])->get()->pluck('stripe_amount')->sum();
            // $marketStripeFee = MarketDataPurchaseDetail::whereBetween('created_at', [$startData, $endData])->get()->pluck('stripe_amount')->sum();
            // $monthlyStripeFee = MonthlyPay::whereBetween('created_at', [$startData, $endData])->get()->pluck('stripe_amount')->sum();

            // $paymetnGatewayFee = (double) $packageStripeFee + (double) $marketStripeFee + (double) $monthlyStripeFee;

            // $other_fee = 0;
            // $totalAmount = (double) $challangesTotalPrice - ((double) $marketDataTotalPrice + (double) $paymetnGatewayFee + (double) $other_fee);

            // $systemCut = config('app.system_cut');
            // $yourCut = config('app.your_cut');

            // $systemCutValue = (double) ($systemCut / 100) * $totalPaymentget;
            // $yourCutValue = (double) ($yourCut / 100) * $totalPaymentget;

            // $inserData = array(

            //     'challenges_amount' => (double) $challangesTotalPrice,
            //     'market_amount' => (double) $marketDataTotalPrice,
            //     'payment_getaway_amount' => (double) $paymetnGatewayFee,
            //     'other_amount' => (double) $other_fee,
            //     'total_amount' => (double) $totalAmount,
            //     'system_cut_amount' => (double) $systemCutValue,
            //     'your_cut_amount' => (double) $yourCutValue,
            // );

            // PlatfromPayment::create($inserData);

        })->monthly();
        // })->everyMinute();

        $schedule->call(function () {

            $startData = Carbon::now()->startOfMonth();
            $endData = Carbon::now()->endOfMonth();
            $challangesTotalPrice = PackagePurchaseAccountDetail::with('cardChallenge')->whereBetween('created_at', [$startData, $endData])->get()->pluck('cardChallenge.price')->sum();
            $marketDataTotalPrice = MarketDataPurchaseDetail::whereBetween('created_at', [$startData, $endData])->get()->sum('package_price');
            $totalPaymentget = (double) $challangesTotalPrice + (double) $marketDataTotalPrice;

            $packageStripeFee = PackagePurchaseAccountDetail::whereBetween('created_at', [$startData, $endData])->get()->pluck('stripe_amount')->sum();
            $marketStripeFee = MarketDataPurchaseDetail::whereBetween('created_at', [$startData, $endData])->get()->pluck('stripe_amount')->sum();
            $monthlyStripeFee = MonthlyPay::whereBetween('created_at', [$startData, $endData])->get()->pluck('stripe_amount')->sum();

            $paymetnGatewayFee = (double) $packageStripeFee + (double) $marketStripeFee + (double) $monthlyStripeFee;

            $other_fee = 0;
            $totalAmount = (double) $challangesTotalPrice - ((double) $marketDataTotalPrice + (double) $paymetnGatewayFee + (double) $other_fee);

            $systemCut = config('app.system_cut');
            $yourCut = config('app.your_cut');

            // $systemCutValue = (double) ($systemCut / 100) * $totalPaymentget;
            // $yourCutValue = (double) ($yourCut / 100) * $totalPaymentget;

            $systemCutValue = (double) ($systemCut / 100) * $totalAmount;
            $yourCutValue = (double) ($yourCut / 100) * $totalAmount;

            //new code for insert total usr and purchase data
            $saleVsUser = PackagePurchaseAccountDetail::whereBetween('created_at', [$startData, $endData])->get();
            $totalSell = $saleVsUser->count();
            $totalUser = $saleVsUser->pluck('user_id ')->unique()->count();
            //new code for insert total usr and purchase data

            $inserData = array(

                'challenges_amount' => (double) $challangesTotalPrice,
                'market_amount' => (double) $marketDataTotalPrice,
                'payment_getaway_amount' => (double) $paymetnGatewayFee,
                'other_amount' => (double) $other_fee,
                'total_amount' => (double) $totalAmount,
                'system_cut_amount' => (double) $systemCutValue,
                'your_cut_amount' => (double) $yourCutValue,

                'total_purchase' => (double) $totalSell,
                'total_user' => (double) $totalUser,
            );

            $getPlatfromData = PlatfromPayment::whereBetween('created_at', [$startData, $endData])->first();

            if (!empty($getPlatfromData)) {
                $getPlatfromData->update($inserData);
            } else {
                PlatfromPayment::create($inserData);
            }

        })->everyFiveMinutes();
        // })->everyMinute();

        $schedule->call(function () {

            $currentDate = Carbon::now();
            $noticeDate = $currentDate->addDay(3);
            $formateCurrentDate = $currentDate->format('Y-m-d');
            $formateNoticeDate = $noticeDate->format('Y-m-d');

            $userID = UserDetail::where('status', 1)->whereDate('end_date', $formateNoticeDate)->get()->pluck('user_id');

            foreach ($userID as $key => $value) {
                $user = User::find($value);

                $mailObject = new stdClass;
                $mailObject->name = $user?->userDetail?->first_name . ' ' . $user?->userDetail?->last_name;
                $mailObject->url = url('cancel/subscription/' . $user->uuid);

                try {
                    Mail::to($user?->email)->send(new SubscriptionCancel($mailObject));
                } catch (Exception $e) {
                    $e->getMessage();
                }

            }

        })->daily();
        // })->everyMinute();

        // $schedule->call(function () {

        //     $go_lang_url = config('app.go_lang_url');
        //     $go_lang_url = $go_lang_url . "futures/user/challenge/details";

        //     $response = Http::post($go_lang_url, [
        //         "cqgSalesSeries" => "29160",
        //     ]);

        //     $challengeData = $response->object()->challenges;

        //     //

        //     foreach ($challengeData as $key => $cvalue) {
        //         $buingPower = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $cvalue->account_size);
        //         $cardChallenge = CardChallenge::where('buying_power', $buingPower)->first();

        //         $rule1Des = $cvalue->rule1_description;
        //         $rule1Val = $cvalue->rule1_maximum;

        //         $minimumday = CardHeadTitle::firstOrCreate(
        //             ['title' => "Minimum Challenge Days"],
        //         );

        //         $miniMumCardHead = CardHead::firstOrCreate(
        //             ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $minimumday->id],
        //         );

        //         $minimumdayValue = CardSubHeadTitle::firstOrCreate(
        //             ['card_head_title_id' => $minimumday->id, 'title' => $cvalue->minimum_days],
        //         );

        //         CardHeadSubTitle::firstOrCreate(
        //             ['card_head_id' => $miniMumCardHead->id, 'card_sub_head_title_id' => $minimumdayValue->id],
        //         );

        //         $profitTarget = CardHeadTitle::firstOrCreate(
        //             ['title' => "Profit Target"],
        //         );

        //         $profitTargetValue = CardSubHeadTitle::firstOrCreate(
        //             ['card_head_title_id' => $profitTarget->id, 'title' => $cvalue->profit_target],
        //         );

        //         $profitCardHead = CardHead::firstOrCreate(
        //             ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $profitTarget->id],
        //         );

        //         CardHeadSubTitle::firstOrCreate(
        //             ['card_head_id' => $profitCardHead->id, 'card_sub_head_title_id' => $profitTargetValue->id],
        //         );

        //         $cdTId = CardHeadTitle::firstOrCreate(
        //             ['title' => $rule1Des],
        //         );

        //         $cdSubHeadId = CardSubHeadTitle::firstOrCreate(
        //             ['card_head_title_id' => $cdTId->id, 'title' => $rule1Val],
        //         );

        //         $cardHeadId = CardHead::firstOrCreate(
        //             ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $cdTId->id],
        //         );

        //         CardHeadSubTitle::firstOrCreate(
        //             ['card_head_id' => $cardHeadId->id, 'card_sub_head_title_id' => $cdSubHeadId->id],
        //         );

        //         $rule1Des = $cvalue->rule2_description;
        //         $rule1Val = $cvalue->rule2_maximum;

        //         $cdTId = CardHeadTitle::firstOrCreate(
        //             ['title' => $rule1Des],
        //         );
        //         $cdSubHeadId = CardSubHeadTitle::firstOrCreate(
        //             ['card_head_title_id' => $cdTId->id, 'title' => $rule1Val],
        //         );

        //         $cardHeadId = CardHead::firstOrCreate(
        //             ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $cdTId->id],
        //         );

        //         CardHeadSubTitle::firstOrCreate(
        //             ['card_head_id' => $cardHeadId->id, 'card_sub_head_title_id' => $cdSubHeadId->id],
        //         );

        //         $rule1Des = $cvalue->rule3_description;
        //         $rule1Val = $cvalue->rule3_maximum;

        //         $cdTId = CardHeadTitle::firstOrCreate(
        //             ['title' => $rule1Des],
        //         );
        //         $cdSubHeadId = CardSubHeadTitle::firstOrCreate(
        //             ['card_head_title_id' => $cdTId->id, 'title' => $rule1Val],
        //         );

        //         $cardHeadId = CardHead::firstOrCreate(
        //             ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $cdTId->id],
        //         );

        //         CardHeadSubTitle::firstOrCreate(
        //             ['card_head_id' => $cardHeadId->id, 'card_sub_head_title_id' => $cdSubHeadId->id],
        //         );

        //     }

        // })->daily();

        $schedule->call('App\Http\Controllers\Admin\HistoryController@storeDailyRecord')
            ->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
