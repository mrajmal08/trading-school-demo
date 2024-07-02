<?php

namespace App\Http\Controllers\api;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\NewUserRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['data'] = [
            'total' => Auth::user()->unreadNotifications->count(),
            'notifications'=> auth()->user()->unreadNotifications->pluck('data')
        ];
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find(auth()->user()->id);
        return event(new NewUserRegistration($user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    public function ReadAllNotification()
    {
        $userUnreadNotification = auth()->user()->unreadNotifications->markAsRead();
        return response()->json([
            'status' => 200,
            'message' => "Notification read successfully",
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // public function demo(Request $request){

    //     $stripe = new \Stripe\StripeClient(
    //         env('STRIPE_SECRET_KEY')
    //     );
    //     $customer = $stripe->customers->create([
    //         'email'=> $request->email,
    //         'payment_method' => 'pm_card_visa',
    //         'invoice_settings' => ['default_payment_method'=>'pm_card_visa'],
    //     ]);

    //     $subscription = $stripe->subscriptions->create([
    //         'customer' => $customer->id,
    //         'items' => [
    //           ['price' => 'price_1MHhyhG8eX1XeOGiYIZxJyc2'],
    //         ],
    //       ]);

    //     return ['data'=>$subscription];
    // }
}
