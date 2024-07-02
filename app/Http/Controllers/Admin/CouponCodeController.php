<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class CouponCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $couponCodeList = CouponCode::when($search, function ($query) use ($search) {
            $query->orwhere('promotion_name', 'like', "%$search%");
            $query->orwhere('coupon_id', 'like', "%$search%");
        })->paginate(10);
        return view("pages.admin.coupon-code.coupon-code-list", compact('couponCodeList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.admin.coupon-code.coupon-code-create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'promotion_name' => 'required',
            'coupon_code' => 'required',
            'amount' => 'required',
            'date_range' => 'required',
            'currency' => 'required',
            'max_use' => 'required',
            'total_number' => 'nullable',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $data = $validator->validated();
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        $stripe = new StripeClient(
            env('STRIPE_SECRET_KEY')
        );

        if ($request->coupon_amount_policy == "amount_off") {
            $amount = $request->amount * 100;
        } else {
            $amount = $request->amount;
        }

        $carbon = Carbon::parse($request->date_range);
        $unixTimestamp = $carbon->timestamp;
        // dd($unixTimestamp);

        $couponData = $stripe->coupons->create([
            'name' => $request->coupon_code . '' . substr(uniqid(), 0, 8),
            $request->coupon_amount_policy => $amount,
            'currency' => $request->currency,
            'duration' => $request->max_use,
            // 'redeem_by' => (int)strtotime($request->date_range),
            // 'redeem_by' => (int) $unixTimestamp,
            'metadata' => [
                'start_date' => $unixTimestamp, // Replace with your desired start date
            ],
        ]);

        $data['coupon_code'] = $couponData->name;
        $data['coupon_id'] = $couponData->id;

        if ($request->max_use == 'forever') {
            $data['total_number'] = $request->total_number ? $request->total_number : 0;
        } else {
            $data['total_number'] = 1;
        }

        $row = CouponCode::updateOrCreate($data);

        if ($row) {
            return Redirect::route('coupon-code.index')->with(['success' => 'Coupon code create successfully']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CouponCode $couponCode)
    {
        return view("pages.admin.coupon-code.coupon-code-edit", compact('couponCode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CouponCode $couponCode)
    {
        $validator = Validator::make($request->all(), [
            'promotion_name' => 'required',
            'coupon_code' => 'required',
            'amount' => 'required',
            'date_range' => 'required',
            'currency' => 'required',
            'max_use' => 'required',
            'total_number' => 'nullable',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->validated();

        if ($couponCode->coupon_code != $request->coupon_code) {
            $stripe = new StripeClient(
                env('STRIPE_SECRET_KEY')
            );

            $couponData = $stripe->coupons->update(
                $couponCode->coupon_id,
                [
                    'name' => $request->coupon_code . '' . substr(uniqid(), 0, 8),
                ]
            );
            $data['coupon_code'] = $couponData->name;
        }
        if ($request->max_use == 'forever') {
            $data['total_number'] = $request->total_number ? $request->total_number : 0;
        } else {
            $data['total_number'] = 1;
        }

        $row = $couponCode->update($data);

        if ($row) {
            return Redirect::route('coupon-code.index')->with(['success' => 'Coupon code update successfully']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CouponCode $couponCode)
    {
        $stripe = new StripeClient(
            env('STRIPE_SECRET_KEY')
        );
        $stripe->coupons->delete($couponCode->coupon_id, []);

        if ($couponCode->delete()) {
            return ['success' => 'Coupon code deleted successfully'];
        }
    }
}
