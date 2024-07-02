<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SubscriptionCancel;
use App\Models\SubscribeLog;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use stdClass;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
//    public function showLoginForm()
//    {
//        return view('admin.login');
//    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard('admin')->logout();

        // $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function emailNotice()
    {
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

    }

    public function cancelSubscription(User $user)
    {
        $stripeDetail = SubscribeLog::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        $userDetail = UserDetail::where('user_id', $user->id)->first();
        $stripeConfigKye = config('app.stripe_secret_kye');
        $stripe = new \Stripe\StripeClient(
            $stripeConfigKye
        );

        try {
            $data = $stripe->subscriptions->cancel(
                $stripeDetail->stripe_subscription_id,
                []
            );

            DB::transaction(function () use ($data, $stripeDetail, $userDetail) {
                $stripeDetail->payment_status = $data->status;
                $stripeDetail->save();
                $userDetail->status = 0;
                $userDetail->save();
            });
        } catch (Exception $e) {
            $e->getMessage();
        }

        $front_end_url = config('app.frontend_url').'/login';
        return Redirect::to($front_end_url)->with(['success' => "Subscription Cancel"]);

    }
}
