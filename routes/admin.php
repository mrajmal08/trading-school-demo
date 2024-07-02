<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CardHeadTitleController;
use App\Http\Controllers\Admin\CardSubHeadTitleController;
use App\Http\Controllers\Admin\ChallengeController;
use App\Http\Controllers\Admin\CouponCodeController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\MarketDataFeedController;
use App\Http\Controllers\Admin\PlatformPaymentController;
use App\Http\Controllers\Admin\RulesController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;

/*=========================admin login=============*/
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'checksystem']], function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.risk_manage');
    })->name('admin.home');

    Route::get('staff', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.staff');
    Route::get('logout', [App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('admin.logout');
    Route::get('teacher-list', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('teacher.list');
    Route::get('staff/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('teacher.create');
    Route::get('teacher/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('teacher.edit');
    Route::post('teacher/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('teacher.update');
    Route::delete('teacher-destroy/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('teacher.destroy');
    Route::post('teacher-store', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teacher.store');
    Route::get('user-list', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('user.list');
    Route::get('user-edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('user.edit');
    Route::post('user-update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('user.update');
    Route::get('user-detail/{user:uuid}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('user.detail');
    Route::get('user-balance', [App\Http\Controllers\Admin\UserController::class, 'balance'])->name('user.balance');
    Route::get('account-list', [App\Http\Controllers\Admin\AccountController::class, 'index'])->name('account.list');
    Route::get('account-list-log', [App\Http\Controllers\Admin\AccountController::class, 'accountListLog'])->name('account.list.log');
    Route::post('account-balance-update', [App\Http\Controllers\Admin\AccountController::class, 'accountBalanceUpdate'])->name('account.balance.update');

    Route::get('ajax/matrix/{pacageid}/{selectVal}', [App\Http\Controllers\Admin\UserController::class, 'ajaxMatrix'])->name('user.matrix');
    Route::group(['prefix' => 'challenges'], function () {
        Route::get('/', [ChallengeController::class, 'index'])->name('admin.challenges');
        Route::get('/create', [ChallengeController::class, 'create'])->name('admin.challenges.create');
        Route::post('/store', [ChallengeController::class, 'store'])->name('admin.challenges.store');
        Route::get('/details/{id}', [ChallengeController::class, 'show'])->name('admin.challenges.show');
        Route::get('/edit/{id}', [ChallengeController::class, 'edit'])->name('admin.challenges.edit');
        Route::put('/update/{id}', [ChallengeController::class, 'update'])->name('admin.challenges.update');
        Route::delete('/destroy/{id}', [ChallengeController::class, 'destroy'])->name('admin.challenges.destroy');
        Route::get('/get/challenge', [ChallengeController::class, 'getChallenge'])->name('admin.getChallenge');
    });

    Route::get('payment', [PlatformPaymentController::class, 'monthlyPay'])->name('admin.payment');
    Route::get('select-state', [App\Http\Controllers\Admin\TeacherController::class, 'stateList'])->name('admin.select.state');
    Route::get('account/activation/{packagePurchaseAccountDetail:uuid}', [AccountController::class, 'packagePurchaseAccountActivate'])->name('account.packagePurchaseAccountActivate');
    Route::post('account/reset', [AccountController::class, 'accountReset'])->name('account.accountReset');
    Route::get('dashboard/educatorlist', [App\Http\Controllers\AdminController::class, 'searchEducator'])->name('admin.searchEducator');
    Route::get('account/forced/{packagePurchaseAccountDetail:uuid}', [AccountController::class, 'forcedAccount'])->name('account.forcedAccount');
    Route::get('account/live/{packagePurchaseAccountDetail:uuid}', [AccountController::class, 'activeLiveAccount'])->name('account.activeLiveAccount');

    /*========================Web setting url=====================*/
    Route::get('web-setting-index', [App\Http\Controllers\Admin\WebSettingController::class, 'index'])->name('web.setting.index');
    Route::get('web-setting-create', [App\Http\Controllers\Admin\WebSettingController::class, 'create'])->name('web.setting.create');
    Route::post('web-setting-store', [App\Http\Controllers\Admin\WebSettingController::class, 'store'])->name('web.setting.store');
    Route::get('web-setting-edit/{id}', [App\Http\Controllers\Admin\WebSettingController::class, 'edit'])->name('web.setting.edit');
    Route::post('web-setting-update/{id}', [App\Http\Controllers\Admin\WebSettingController::class, 'update'])->name('web.setting.update');

    /*========================Notification=====================*/
    Route::get('notification-create', [App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notification.create');
    Route::post('notification-store', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notification.store');

    /*========================Blog=====================*/
    Route::get('blog-list', [App\Http\Controllers\Admin\BlogController::class, 'index'])->name('blog.index');
    Route::get('blog-create', [App\Http\Controllers\Admin\BlogController::class, 'create'])->name('blog.create');
    Route::post('blog-store', [App\Http\Controllers\Admin\BlogController::class, 'store'])->name('blog.store');
    Route::get('blog-edit/{id}', [App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('blog.edit');
    Route::delete('blog-destroy/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('blog.destroy');
    Route::post('blog-update/{id}', [App\Http\Controllers\Admin\BlogController::class, 'update'])->name('blog.update');
    Route::get('tags', [App\Http\Controllers\Admin\BlogController::class, 'tags'])->name('blog.tags');

    /*========================Faq=====================*/
    Route::get('faq-list', [App\Http\Controllers\Admin\FaqController::class, 'index'])->name('faq.index');
    Route::get('faq-create', [App\Http\Controllers\Admin\FaqController::class, 'create'])->name('faq.create');
    Route::post('faq-store', [App\Http\Controllers\Admin\FaqController::class, 'store'])->name('faq.store');
    Route::get('faq-edit/{id}', [App\Http\Controllers\Admin\FaqController::class, 'edit'])->name('faq.edit');
    Route::post('faq-update/{id}', [App\Http\Controllers\Admin\FaqController::class, 'update'])->name('faq.update');
    Route::delete('faq-destroy/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('faq.destroy');

    /*========================Ruels=====================*/
    Route::get('rules-list', [RulesController::class, 'index'])->name('rules.index');
    Route::get('rules-create', [RulesController::class, 'create'])->name('rules.create');
    Route::post('rules-store', [RulesController::class, 'store'])->name('rules.store');
    Route::get('rules-edit/{id}', [RulesController::class, 'edit'])->name('rules.edit');
    Route::post('rules-update/{id}', [RulesController::class, 'update'])->name('rules.update');
    Route::delete('rules-destroy/{id}', [RulesController::class, 'destroy'])->name('rules.destroy');

    /*========================Blog=====================*/
    Route::get('platform-payment-list', [PlatformPaymentController::class, 'index'])->name('platform.payment.index');
    Route::get('stripe/payment/check/{sripeID}', [PlatformPaymentController::class, 'checkMonthlyPayment'])->name('platform.payment.check');

    Route::get('receive/platform/payment/{platfromPayment:uuid}', [PlatformPaymentController::class, 'recievePlatformPayment'])->name('platform.recievePlatformPayment');

    /*========================Service=====================*/
    Route::get('service-list', [ServiceController::class, 'index'])->name('service.index');
    Route::get('service-create', [ServiceController::class, 'create'])->name('service.create');
    Route::post('service-store', [ServiceController::class, 'store'])->name('service.store');
    Route::get('service-edit/{service:uuid}', [ServiceController::class, 'edit'])->name('service.edit');
    Route::post('service-update/{service:uuid}', [ServiceController::class, 'update'])->name('service.update');
    Route::delete('service-destroy/{service:uuid}', [ServiceController::class, 'destroy'])->name('service.destroy');

    /*========================Service=====================*/
    Route::get('card-head-title-list', [CardHeadTitleController::class, 'index'])->name('card-head-title.index');
    Route::get('card-head-title-create', [CardHeadTitleController::class, 'create'])->name('card-head-title.create');
    Route::post('card-head-title-store', [CardHeadTitleController::class, 'store'])->name('card-head-title.store');
    Route::get('card-head-title-edit/{cardHeadTitle:uuid}', [CardHeadTitleController::class, 'edit'])->name('card-head-title.edit');
    Route::post('card-head-title-update/{cardHeadTitle:uuid}', [CardHeadTitleController::class, 'update'])->name('card-head-title.update');
    Route::delete('card-head-title-destroy/{cardHeadTitle:uuid}', [CardHeadTitleController::class, 'destroy'])->name('card-head-title.destroy');

    /*========================Coupon code=====================*/
    Route::get('coupon-code-list', [CouponCodeController::class, 'index'])->name('coupon-code.index');
    Route::get('coupon-code-create', [CouponCodeController::class, 'create'])->name('coupon-code.create');
    Route::post('coupon-code-store', [CouponCodeController::class, 'store'])->name('coupon-code.store');
    Route::get('coupon-code-edit/{couponCode:uuid}', [CouponCodeController::class, 'edit'])->name('coupon-code.edit');
    Route::post('coupon-code-update/{couponCode:uuid}', [CouponCodeController::class, 'update'])->name('coupon-code.update');
    Route::delete('coupon-code-destroy/{couponCode:uuid}', [CouponCodeController::class, 'destroy'])->name('coupon-code.destroy');

    /*========================card sub head title code=====================*/
    Route::get('card-sub-head-title-list', [CardSubHeadTitleController::class, 'index'])->name('card-sub-head-title.index');
    Route::get('card-sub-head-title-create', [CardSubHeadTitleController::class, 'create'])->name('card-sub-head-title.create');
    Route::post('card-sub-head-title-store', [CardSubHeadTitleController::class, 'store'])->name('card-sub-head-title.store');
    Route::get('card-sub-head-title-edit/{cardSubHeadTitle:uuid}', [CardSubHeadTitleController::class, 'edit'])->name('card-sub-head-title.edit');
    Route::post('card-sub-head-title-update/{cardSubHeadTitle:uuid}', [CardSubHeadTitleController::class, 'update'])->name('card-sub-head-title.update');
    Route::delete('card-sub-head-title-destroy/{cardSubHeadTitle:uuid}', [CardSubHeadTitleController::class, 'destroy'])->name('card-sub-head-title.destroy');
    Route::get('/get_sub_titles/{title_id}', [CardSubHeadTitleController::class, 'get_sub_titles'])->name('admin.get_sub_titles');

    /*========================New Challenge Create=====================*/
    // Route::get('challenge-list', [ChallengeController::class, 'index'])->name('challenge.index');
    // Route::get('challenge-create', [ChallengeController::class, 'create'])->name('challenge.create');
    // Route::get('challenge', fn() => view('pages.admin.challenge'))->name('admin.challenge');

    /*========================New Challenge Create=====================*/
    Route::get('market-data-feed-list', [MarketDataFeedController::class, 'index'])->name('market-data-feed.index');
    Route::get('market-data-feed-list/{marketData:uuid}', [MarketDataFeedController::class, 'edit'])->name('market-data-feed.edit');
    Route::post('market-data-feed-list/{marketData:uuid}', [MarketDataFeedController::class, 'update'])->name('market-data-feed.update');
    Route::get('market-data-purchase-list/', [MarketDataFeedController::class, 'marketDataPurchaseList'])->name('marketDataPurchaseList');
    Route::get('market-data-purchase-log/{id?}', [MarketDataFeedController::class, 'marketDataPurchaselog'])->name('marketDataPurchaselog');
    Route::get('marketdata/reset/{marketDataPurchaseDetail:uuid}', [MarketDataFeedController::class, 'reProcessMarketData'])->name('reProcessMarketData');

    Route::get('/risk-manage-view', [HistoryController::class, 'viewRiskManage'])->name('admin.risk_manage');
    Route::get('/historical-challenges', [HistoryController::class, 'challengeHistories'])->name('admin.historical_challenges');
    Route::get('/reset-challenge-history/{id}', [HistoryController::class, 'getResetHistory'])->name('admin.reset-history');
    Route::get('/challenge_history/{packagePurchaseAccountDetail:uuid}', [HistoryController::class, 'getUserChallengesHistory'])->name('admin.challenge_history');
    Route::get('/risk-manger-details/{id}', [HistoryController::class, 'getRiskChallengeDetails'])->name('admin.risk_details');
});

Route::get('store/daily/data', [HistoryController::class, 'storeDailyRecord'])->name('daily.snapshots');
