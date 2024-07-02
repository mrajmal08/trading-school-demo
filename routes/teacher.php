<?php

use App\Http\Controllers\Teacher\ChallengeController;
use App\Http\Controllers\Teacher\CouponCodeController;
use App\Http\Controllers\Teacher\FaqController;
use App\Http\Controllers\Teacher\HistoryController as TeacherHistoryController;
use App\Http\Controllers\Teacher\MarketDataFeedController;
use App\Http\Controllers\Teacher\PlatformPaymentController;
use App\Http\Controllers\Teacher\RulesController;
use App\Http\Controllers\Teacher\StaffController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'teacher', 'middleware' => ['auth:teacher', 'checksystem']], function () {
    Route::get('/home', fn() => redirect()->route('teacher.trader.list'))->name('home');
    Route::get('/trader-list', [App\Http\Controllers\Teacher\UserController::class, 'index'])->name('teacher.trader.list');
    Route::get('trader-edit/{id}', [App\Http\Controllers\Teacher\UserController::class, 'edit'])->name('teacher.trader.edit');
    Route::post('trader-update/{id}', [App\Http\Controllers\Teacher\UserController::class, 'update'])->name('teacher.trader.update');

    Route::get('trader-details/{user:uuid}', [App\Http\Controllers\Teacher\UserController::class, 'show'])->name('teacher.trader.detail');
    Route::post('account-balance-update', [App\Http\Controllers\Teacher\AccountController::class, 'accountBalanceUpdate'])->name('teacher.account.balance.update');
    Route::get('select-state', [App\Http\Controllers\Teacher\UserController::class, 'stateList'])->name('teacher.select.test');

    Route::post('account/reset', [App\Http\Controllers\Teacher\AccountController::class, 'accountReset'])->name('teacher.account.accountReset');
    Route::get('account-list', [App\Http\Controllers\Teacher\AccountController::class, 'index'])->name('teacher.account.list');
    Route::get('account/activation/{packagePurchaseAccountDetail:uuid}', [App\Http\Controllers\Teacher\AccountController::class, 'packagePurchaseAccountActivate'])->name('teacher.account.packagePurchaseAccountActivate');
    Route::get('account-list-log', [App\Http\Controllers\Teacher\AccountController::class, 'accountListLog'])->name('teacher.account.list.log');
    Route::get('account/forced/{packagePurchaseAccountDetail:uuid}', [App\Http\Controllers\Teacher\AccountController::class, 'forcedAccount'])->name('teacher.account.forcedAccount');
    Route::get('account/live/{packagePurchaseAccountDetail:uuid}', [App\Http\Controllers\Teacher\AccountController::class, 'activeLiveAccount'])->name('teacher.account.activeLiveAccount');
    Route::get('market-data-purchase-list/', [App\Http\Controllers\Teacher\MarketDataFeedController::class, 'marketDataPurchaseList'])->name('teacher.marketDataPurchaseList');
    Route::get('marketdata/reset/{marketDataPurchaseDetail:uuid}', [App\Http\Controllers\Teacher\MarketDataFeedController::class, 'reProcessMarketData'])->name('teacher.reProcessMarketData');
    Route::get('market-data-purchase-log/{id?}', [App\Http\Controllers\Teacher\MarketDataFeedController::class, 'marketDataPurchaselog'])->name('teacher.marketDataPurchaselog');

    /*========================Notification=====================*/
    Route::get('notification-create', [App\Http\Controllers\Teacher\NotificationController::class, 'create'])->name('teacher.notification.create');
    Route::post('notification-store', [App\Http\Controllers\Teacher\NotificationController::class, 'store'])->name('teacher.notification.store');
    Route::get('user-balance', [App\Http\Controllers\Teacher\UserController::class, 'balance'])->name('teacher.balance');
    Route::get('ajax/matrix/{pacageid}/{selectVal}', [App\Http\Controllers\Teacher\UserController::class, 'ajaxMatrix'])->name('teacher.user.matrix');
    Route::get('/risk-manage-view', [TeacherHistoryController::class, 'viewRiskManage'])->name('teacher.risk_manage');
    Route::get('/historical-challenges', [TeacherHistoryController::class, 'challengeHistories'])->name('teacher.historical_challenges');
    Route::get('/reset-challenge-history/{id}', [TeacherHistoryController::class, 'getResetHistory'])->name('teacher.reset-history');
    Route::get('/challenge_history/{packagePurchaseAccountDetail:uuid}', [TeacherHistoryController::class, 'getUserChallengesHistory'])->name('teacher.challenge_history');
    Route::get('/risk-manger-details/{id}', [TeacherHistoryController::class, 'getRiskChallengeDetails'])->name('teacher.risk_details');

    /*========================Coupon code=====================*/
    Route::get('coupon-code-list', [CouponCodeController::class, 'index'])->name('teacher.coupon-code.index');
    Route::get('coupon-code-create', [CouponCodeController::class, 'create'])->name('teacher.coupon-code.create');
    Route::post('coupon-code-store', [CouponCodeController::class, 'store'])->name('teacher.coupon-code.store');
    Route::get('coupon-code-edit/{couponCode:uuid}', [CouponCodeController::class, 'edit'])->name('teacher.coupon-code.edit');
    Route::post('coupon-code-update/{couponCode:uuid}', [CouponCodeController::class, 'update'])->name('teacher.coupon-code.update');
    Route::delete('coupon-code-destroy/{couponCode:uuid}', [CouponCodeController::class, 'destroy'])->name('teacher.coupon-code.destroy');

    Route::get('staff', [StaffController::class, 'index'])->name('staffs');
    Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('staff/store', [StaffController::class, 'store'])->name('staff.store');
    Route::get('staff/edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
    Route::post('staff/update/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('staff/destroy/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::get('select-state', [StaffController::class, 'stateList'])->name('staff.select.state');

    /*======================== Billing & Payments =====================*/
    Route::get('payment', [PlatformPaymentController::class, 'monthlyPay'])->name('teacher.payment');
    Route::get('platform-payment-list', [PlatformPaymentController::class, 'index'])->name('teacher.platform.payment.index');
    Route::get('stripe/payment/check/{sripeID}', [PlatformPaymentController::class, 'checkMonthlyPayment'])->name('teacher.platform.payment.check');
    Route::get('receive/platform/payment/{platfromPayment:uuid}', [PlatformPaymentController::class, 'recievePlatformPayment'])->name('teacher.platform.recievePlatformPayment');

    /*========================Faq=====================*/
    Route::get('faq-list', [FaqController::class, 'index'])->name('teacher.faq.index');
    Route::get('faq-create', [FaqController::class, 'create'])->name('teacher.faq.create');
    Route::post('faq-store', [FaqController::class, 'store'])->name('teacher.faq.store');
    Route::get('faq-edit/{id}', [FaqController::class, 'edit'])->name('teacher.faq.edit');
    Route::post('faq-update/{id}', [FaqController::class, 'update'])->name('teacher.faq.update');
    Route::delete('faq-destroy/{id}', [FaqController::class, 'destroy'])->name('teacher.faq.destroy');

    /*========================Ruels=====================*/
    Route::get('rules-list', [RulesController::class, 'index'])->name('teacher.rules.index');
    Route::get('rules-create', [RulesController::class, 'create'])->name('teacher.rules.create');
    Route::post('rules-store', [RulesController::class, 'store'])->name('teacher.rules.store');
    Route::get('rules-edit/{id}', [RulesController::class, 'edit'])->name('teacher.rules.edit');
    Route::post('rules-update/{id}', [RulesController::class, 'update'])->name('teacher.rules.update');
    Route::delete('rules-destroy/{id}', [RulesController::class, 'destroy'])->name('teacher.rules.destroy');

    /*========================New Challenge Create=====================*/
    Route::get('market-data-feed-list', [MarketDataFeedController::class, 'index'])->name('teacher.market-data-feed.index');
    Route::get('market-data-feed-list/{marketData:uuid}', [MarketDataFeedController::class, 'edit'])->name('teacher.market-data-feed.edit');
    Route::post('market-data-feed-list/{marketData:uuid}', [MarketDataFeedController::class, 'update'])->name('teacher.market-data-feed.update');
    Route::get('market-data-purchase-list/', [MarketDataFeedController::class, 'marketDataPurchaseList'])->name('teacher.marketDataPurchaseList');
    Route::get('market-data-purchase-log/{id?}', [MarketDataFeedController::class, 'marketDataPurchaselog'])->name('teacher.marketDataPurchaselog');
    Route::get('marketdata/reset/{marketDataPurchaseDetail:uuid}', [MarketDataFeedController::class, 'reProcessMarketData'])->name('teacher.reProcessMarketData');

    Route::group(['prefix' => 'challenges'], function () {
        Route::get('/', [ChallengeController::class, 'index'])->name('teacher.challenges');
        Route::get('/create', [ChallengeController::class, 'create'])->name('teacher.challenges.create');
        Route::post('/store', [ChallengeController::class, 'store'])->name('teacher.challenges.store');
        Route::get('/details/{id}', [ChallengeController::class, 'show'])->name('teacher.challenges.show');
        // Route::get('/edit/{id}', [ChallengeController::class, 'edit'])->name('teacher.challenges.edit');
        // Route::put('/update/{id}', [ChallengeController::class, 'update'])->name('teacher.challenges.update');
        // Route::delete('/destroy/{id}', [ChallengeController::class, 'destroy'])->name('teacher.challenges.destroy');
        Route::get('/get/challenge', [ChallengeController::class, 'getChallenge'])->name('teacher.getChallenge');
    });

    /*========================Blog=====================*/
    Route::get('blog-list', [App\Http\Controllers\Teacher\BlogController::class, 'index'])->name('teacher.blog.index');
    Route::get('blog-create', [App\Http\Controllers\Teacher\BlogController::class, 'create'])->name('teacher.blog.create');
    Route::post('blog-store', [App\Http\Controllers\Teacher\BlogController::class, 'store'])->name('teacher.blog.store');
    Route::get('blog-edit/{id}', [App\Http\Controllers\Teacher\BlogController::class, 'edit'])->name('teacher.blog.edit');
    Route::delete('blog-destroy/{id}', [\App\Http\Controllers\Teacher\BlogController::class, 'destroy'])->name('teacher.blog.destroy');
    Route::post('blog-update/{id}', [App\Http\Controllers\Teacher\BlogController::class, 'update'])->name('teacher.blog.update');
    Route::get('tags', [App\Http\Controllers\Teacher\BlogController::class, 'tags'])->name('teacher.blog.tags');

    /*========================Web setting url=====================*/
    Route::get('web-setting-index', [App\Http\Controllers\Teacher\WebSettingController::class, 'index'])->name('teacher.web.setting.index');
    Route::get('web-setting-create', [App\Http\Controllers\Teacher\WebSettingController::class, 'create'])->name('teacher.web.setting.create');
    Route::post('web-setting-store', [App\Http\Controllers\Teacher\WebSettingController::class, 'store'])->name('teacher.web.setting.store');
    Route::get('web-setting-edit/{id}', [App\Http\Controllers\Teacher\WebSettingController::class, 'edit'])->name('teacher.web.setting.edit');
    Route::post('web-setting-update/{id}', [App\Http\Controllers\Teacher\WebSettingController::class, 'update'])->name('teacher.web.setting.update');
});

Route::post('teacher/monthly/payment', [PlatformPaymentController::class, 'paymentMonthly'])->name('teacher.paymentMonthly');
