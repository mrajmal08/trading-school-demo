<?php

// use App\Http\Controllers\Admin\AccountController;
// use App\Http\Controllers\Admin\CardHeadTitleController;
// use App\Http\Controllers\Admin\CardSubHeadTitleController;
// use App\Http\Controllers\Admin\ChallengeController;
// use App\Http\Controllers\Admin\CouponCodeController;
// use App\Http\Controllers\Admin\HistoryController;
// use App\Http\Controllers\Admin\MarketDataFeedController;
use App\Http\Controllers\Admin\PlatformPaymentController;
// use App\Http\Controllers\Admin\RulesController;
// use App\Http\Controllers\Admin\ServiceController;
// use App\Http\Controllers\Teacher\HistoryController as TeacherHistoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return redirect()->route("login");
});

Route::prefix("admin")->group(function () {
    Route::get("/login", function () {
        return view('admin.login');
    })->name('admin.login');
});

Auth::routes();
Route::post('teacher', [App\Http\Controllers\Teacher\LoginController::class, 'login'])->name('teacher.login');

/*=========================teacher login=============*/
require __DIR__ . '/teacher.php';

/*=========================admin login=============*/
require __DIR__ . '/admin.php';

Route::get("/admin", function () {
    return redirect()->route("admin.login");
});

Route::post('admin-login', [App\Http\Controllers\Admin\LoginController::class, 'login'])->name('admin.logins');

Route::get('/notification', function () {
    return view('admin.notification');
});

Route::get('email/notice', [App\Http\Controllers\Admin\LoginController::class, 'emailNotice']);
Route::get('cancel/subscription/{user:uuid}', [App\Http\Controllers\Admin\LoginController::class, 'cancelSubscription']);
Route::post('monthly/payment', [PlatformPaymentController::class, 'paymentMonthly'])->name('admin.paymentMonthly');
