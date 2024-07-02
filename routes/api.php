<?php
use App\Http\Controllers\Admin\MarketDataFeedController;
use App\Http\Controllers\api\Authcontroller;
use App\Http\Controllers\api\Blog\BlogController;
use App\Http\Controllers\api\ChallengeController;
use App\Http\Controllers\api\CountryStateController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\EmailSubscriptionController;
use App\Http\Controllers\api\FaqsController;
use App\Http\Controllers\api\FcmController;
use App\Http\Controllers\api\FileUploaderController;
use App\Http\Controllers\api\FrontEndController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\api\RulesController;
use App\Http\Controllers\api\TradeCoreDetailController;
use App\Http\Controllers\api\TradeResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => ['jwt.auth'], 'prefix' => 'v1'], function () {
    /*=====================Rules routes==========*/
    Route::get('rules', [RulesController::class, 'index'])->name('rules.index');
    Route::post('rules', [RulesController::class, 'store'])->name('rules.store');
    Route::get('rules/{rules:uuid}', [RulesController::class, 'show'])->name('rules.show');
    Route::post('rules/{rules:uuid}', [RulesController::class, 'update'])->name('rules.update');
    Route::delete('rules/{rules:uuid}', [RulesController::class, 'destroy'])->name('rules.destroy');

    /*=====================Faq routes==========*/
    Route::get('/faqs', [FaqsController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [FaqsController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{faqs:uuid}', [FaqsController::class, 'show'])->name('faqs.show');
    Route::post('/faqs/{faqs:uuid}', [FaqsController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faqs:uuid}', [FaqsController::class, 'destroy'])->name('faqs.destroy');

    /*===============s3 file upload=========*/
    Route::post('file-upload', [FileUploaderController::class, 'store'])->name('file-upload.store');
    /*===============Test notification=========*/
    Route::post('test-notification', [NotificationController::class, 'store'])->name('test.store');
    Route::get('user-notifications', [NotificationController::class, 'index'])->name('test.index');
    Route::get('read-all-notifications', [NotificationController::class, 'ReadAllNotification'])->name('ReadAllNotification');
});

Route::group(['prefix' => 'v1'], function () {
    /*=====================Email Subscription==========*/
    Route::post('email-subscription', [EmailSubscriptionController::class, 'store'])->name('email.subscription.store');

});

Route::group(['prefix' => 'v1'], function () {
    Route::post('sign-up', [Authcontroller::class, 'signup']);
    Route::post('login', [Authcontroller::class, 'signin']);
    Route::get('logout', [Authcontroller::class, 'logout'])->middleware('jwt.auth');
    Route::get('profile', [Authcontroller::class, 'userDetail'])->middleware('jwt.auth');
    Route::post('reset-password', [Authcontroller::class, 'resetPassword']);
    Route::post('forgot-password', [Authcontroller::class, 'forgetPassword']);

    Route::post('update/profile', [Authcontroller::class, 'updateProfile'])->middleware('jwt.auth');
    Route::post('update/password', [Authcontroller::class, 'changePassword'])->middleware('jwt.auth');

    Route::get('current/packages', [Authcontroller::class, 'getCurrentPackageDetail'])->middleware('jwt.auth');

    //Challenges

    Route::get('services', [ChallengeController::class, 'allServices']);
    Route::get('challenge', [ChallengeController::class, 'challenges']);
    Route::get('challenge/service/{service:uuid}', [ChallengeController::class, 'serviceChallenges']);
    Route::get('challenge/detail/{cardChallenge:uuid}', [ChallengeController::class, 'challengeDetails']);

    // Route::get('services', [ChallengeController::class, 'allServices'])->middleware('jwt.auth');
    // Route::get('challenge', [ChallengeController::class, 'challenges'])->middleware('jwt.auth');
    // Route::get('challenge/service/{service:uuid}', [ChallengeController::class, 'serviceChallenges'])->middleware('jwt.auth');
    // Route::get('challenge/detail/{cardChallenge:uuid}', [ChallengeController::class, 'challengeDetails'])->middleware('jwt.auth');

    Route::post('stipe/success', [PaymentController::class, 'stripe']);
    Route::get('stipe/check', [PaymentController::class, 'checkStripId']);
    Route::get('stipe/check/test', [PaymentController::class, 'checkStripIdTest']);
    Route::post('customer-create-subscription', [PaymentController::class, 'customerCreateSubscription'])->name('customer-create.stripe')->middleware('jwt.auth');

    Route::post('market/data/stipe/success', [PaymentController::class, 'marketDataPurchaseStripe']);
    //DashBoard Api

    Route::post('go/data', [PaymentController::class, 'getGodata']);
    Route::get('go/data/get', [PaymentController::class, 'getGoUserRegResponse']);

    Route::get('increment/coupon/apply/{couponCode}', [PaymentController::class, 'upDateCouponApply']);

    Route::get('trading/day', [DashboardController::class, 'tradingDay'])->middleware('jwt.auth');
    Route::get('account/balance', [DashboardController::class, 'accountBalance'])->middleware('jwt.auth');
    Route::get('trading/result', [DashboardController::class, 'trading'])->middleware('jwt.auth');
    Route::get('all/trades', [DashboardController::class, 'allTrades'])->middleware('jwt.auth');
    Route::get('profit/trades', [DashboardController::class, 'profitTrades'])->middleware('jwt.auth');
    Route::get('losing/trades', [DashboardController::class, 'losingTrades'])->middleware('jwt.auth');
    Route::get('trades/history', [DashboardController::class, 'tradeRecord'])->middleware('jwt.auth');
    Route::get('trades/chart', [DashboardController::class, 'dashboardChart'])->middleware('jwt.auth');

    // Route::get('update/dashboard/data', [DashboardController::class, 'upDateDashBoard'])->middleware('jwt.auth');
    Route::get('update/dashboard/data', [DashboardController::class, 'newDashboardData'])->middleware('jwt.auth');

    Route::post('dashboard/progress/daterange', [DashboardController::class, 'progressDaterange'])->middleware('jwt.auth');

    Route::get('trades/chart/lastday', [DashboardController::class, 'dashboardChartLastDay'])->middleware('jwt.auth');
    Route::get('trades/chart/lastweek', [DashboardController::class, 'dashboardChartLastWeek'])->middleware('jwt.auth');

    Route::get('trades/filter/account/list', [DashboardController::class, 'userTradeAccountList']);

    Route::post('store-token', [FcmController::class, 'updateDeviceToken'])->name('store.token')->middleware('jwt.auth');
    Route::post('send-web-notification', [FcmController::class, 'sendNotification'])->name('send.web-notification');

    Route::get('websettings/footer', [FrontEndController::class, 'webSetting']);
    Route::get('websettings/terms/service', [FrontEndController::class, 'webSettingTermsService']);
    Route::get('websettings/privacy/policy', [FrontEndController::class, 'webSettingPrivacyPolicy']);

    Route::get('country/state', [CountryStateController::class, 'country'])->middleware('jwt.auth');
    Route::get('market/data', [TradeCoreDetailController::class, 'marketData'])->middleware('jwt.auth');
    Route::get('trade/platform', [TradeCoreDetailController::class, 'tradePerformance'])->middleware('jwt.auth');
    Route::get('mt/five/group', [TradeCoreDetailController::class, 'mtFiveGroup'])->middleware('jwt.auth');
    Route::get('trade/account/purchase/detail', [TradeCoreDetailController::class, 'accountPurchaseDetail'])->middleware('jwt.auth');

    Route::post('market/data/update', [TradeCoreDetailController::class, 'udateMarketDataPackage'])->middleware('jwt.auth');

    Route::get('get/all/blog', [BlogController::class, 'index']);
    Route::get('get/single/blog/{blogdetail:uuid}', [BlogController::class, 'show']);
    Route::post('blog/search', [BlogController::class, 'search']);

    Route::get('cqg/reset', [TradeResetController::class, 'cqgMasterResetPassword'])->middleware('jwt.auth');
    Route::get('danger/zone', [TradeResetController::class, 'dangerZone'])->middleware('jwt.auth');

    Route::post('change/payment/type', [TradeResetController::class, 'changePaymentType'])->middleware('jwt.auth');

    Route::post('stipe/cancel', [MarketDataFeedController::class, 'subscription']);
    Route::post('coupon-code', [MarketDataFeedController::class, 'findCoupon']);

    Route::get('account/check', [TradeResetController::class, 'checkCqgAccount']);
    Route::get('cqg/check', [TradeResetController::class, 'cqgStatus']);

    // New code for client Requie Ment 3-23-2023
    Route::get('risk/management', [TradeCoreDetailController::class, 'riskManagement']);
    Route::get('user/risk/management', [TradeCoreDetailController::class, 'riskManagementSingleUser']);

    Route::get('dashboard/history/detail/{packagePurchaseAccountDetail:uuid}', [TradeCoreDetailController::class, 'dahboardHistry'])->middleware('jwt.auth');

    Route::get('test/refund/', [MarketDataFeedController::class, 'testRefund']);

});
// ->middleware('jwt.auth');
