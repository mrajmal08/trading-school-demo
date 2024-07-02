<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Challenges\CardCallangeNormalResource;
use App\Http\Resources\Trading\MarketDataResource;
use App\Http\Resources\UserResource;
// use App\Mail\UserRegister;
use App\Mail\Newreg;
use App\Models\CardChallenge;
use App\Models\MarketData;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserMarketData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use stdClass;

// use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Log;

// use App\Http\Resources\UserDetailResource;

class Authcontroller extends Controller
{
    public function signup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->toArray() as $key => $value) {

                foreach ($value as $key => $errorvalue) {
                    $errorMessage = $errorvalue;
                    break;
                }

                break;
            }

            return response()->json([
                'status' => 400,
                'message' => $errorMessage,

            ], 400);
        }

        $status = DB::transaction(function () use ($request) {
            $user['password'] = Hash::make($request->password);
            $user['email'] = $request->email;
            $newUser = User::create($user);

            $userDetail['first_name'] = $request->first_name;
            $userDetail['last_name'] = $request->last_name;
            $userDetail['user_id'] = $newUser->id;
            UserDetail::create($userDetail);
            return true;
        });

        if ($status == true) {
            $credentialsemail = array("email" => $request->email, "password" => $request->password);
            $token = Auth::guard('api')->attempt($credentialsemail);

            $mailObject = new stdClass;
            $mailObject->name = $request->first_name . ' ' . $request->last_name;
            $front_end_url = config('app.frontend_url') . '/login';
            $mailObject->url = $front_end_url;
            $mailObject->username = $request->email;
            $mailObject->password = $request->password;

            try {
                Mail::to($request->email)->send(new Newreg($mailObject));
            } catch (Exception $e) {
                $e->getMessage();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Account created successfully',
                'access_token' => $token,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Error in Inserting',

            ], 500);

        }

    }

    public function signin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'bail|required|min:8',
        ],
            [
                'email.required' => 'User Id cannot be Empty',

            ]);

        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->toArray() as $key => $value) {

                foreach ($value as $key => $errorvalue) {
                    $errorMessage = $errorvalue;
                    break;
                }

                break;
            }

            return response()->json([
                'status' => 401,
                'message' => 'Validation Error',
                'error' => $errorMessage,

            ], 400);
        } else {
            $email = $request->email;

            $credentialsemail = array("email" => $email, "password" => $request->password);

            if ((Auth::guard('api')->attempt($credentialsemail))) {
                $etoken = Auth::guard('api')->attempt($credentialsemail);

                if (!empty($etoken)) {
                    $token = $etoken;
                }
                $userDetail = Auth::guard('api')->user();

                // call Go lang api

                return response()->json([
                    'status' => 200,
                    'message' => 'Data Found',
                    'data' => new UserResource($userDetail),
                    'access_token' => $token,

                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Credential not match',

                ], 400);
            }
        }
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => true,
            'message' => 'Logout successfully',

        ]);
    }

    public function userDetail()
    {
        $userDetail = Auth::guard('api')->user();

        return response()->json([
            'status' => 200,
            'message' => 'Data Found',
            'data' => new UserResource($userDetail),

        ], 200);

        // if (empty($userDetail)) {

        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'No User Found',

        //     ], 400);

        // } else {
        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'Data Found',
        //         'data' => new UserResource($userDetail),

        //     ], 200);
        // }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|',
        ]);

        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->toArray() as $key => $value) {

                foreach ($value as $key => $errorvalue) {
                    $errorMessage = $errorvalue;
                    break;
                }

                break;
            }

            return response()->json([
                'status' => 401,
                'message' => 'Validation Error',
                'error' => $errorMessage,

            ], 400);
        } else {
            $userDetail = User::where('email', $request->email)->first();
            if (empty($userDetail)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Otp not send',
                ], 400);
            } else {
                $otp = random_int(100000, 999999);
                $userDetail->otp = $otp;
                $userDetail->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Otp Send successfully',
                    'data' => ['otp' => $otp,
                        'uuid' => $userDetail->uuid,
                    ],

                ], 200);
            }
        }

    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'uuid' => 'required',
            'password' => 'bail|required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->toArray() as $key => $value) {

                foreach ($value as $key => $errorvalue) {
                    $errorMessage = $errorvalue;
                    break;
                }

                break;
            }

            return response()->json([
                'status' => 400,
                'message' => $errorMessage,

            ], 400);
        } else {
            $userDetail = User::where('uuid', $request->uuid)->where('otp', $request->otp)->first();

            if (empty($userDetail)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Confirm Password is not match',
                ], 400);
            } else {
                $userDetail->password = Hash::make($request->password);
                $userDetail->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Password change successfully',
                ], 200);
            }
        }
    }

    public function updateProfile(Request $request)
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $validator = Validator::make($request->all(), [
                'first_name' => 'bail|required|string',
                'last_name' => 'bail|required|string',
                'country' => 'bail|required|string',
                'gender' => 'bail|required|string',
                // 'age' => 'bail|required|date',
                'age' => 'bail|required',
            ]);

            if ($validator->fails()) {
                $errorMessage = "";
                foreach ($validator->errors()->toArray() as $key => $value) {

                    foreach ($value as $key => $errorvalue) {
                        $errorMessage = $errorvalue;
                        break;
                    }

                    break;
                }

                return response()->json([
                    'status' => 400,
                    'message' => $errorMessage,

                ], 400);
            }

            $upDateUserDetail = UserDetail::findOrFail($userDetail->userDetail->id);
            $updateData = $validator->validated();
            $dob = Carbon::createFromFormat('Y-m-d', $request->age);
            $updateData['age'] = $dob;
            $updateData['state'] = $request->state;
            if ($request->hasFile('picture')) {
                $userDetail->clearMediaCollection();
                $userDetail->addMedia($request->file('picture'), 's3')->toMediaCollection();
                $mediaItem = $userDetail->getMedia()->first();

                $updateData['picture'] = $mediaItem['original_url'];
            }
            $upDateUserDetail->update($updateData);
            $UpdatuserDetail = User::findOrFail($userDetail->id);
            return response()->json([
                'status' => 200,
                'message' => 'Data Updated',
                'data' => new UserResource($UpdatuserDetail),

            ], 200);
        }

    }

    public function changePassword(Request $request)
    {
        $userDetail = Auth::guard('api')->user();

        if (empty($userDetail)) {

            return response()->json([
                'status' => 400,
                'message' => 'No User Found',

            ], 400);

        } else {

            $validator = Validator::make($request->all(), [
                'old_password' => 'bail|required',
                'password' => 'bail|required|min:8|confirmed',

            ]);

            if ($validator->fails()) {
                $errorMessage = "";
                foreach ($validator->errors()->toArray() as $key => $value) {

                    foreach ($value as $key => $errorvalue) {
                        $errorMessage = $errorvalue;
                        break;
                    }

                    break;
                }

                return response()->json([
                    'status' => 400,
                    'message' => $errorMessage,

                ], 400);
            }

            $currentPassword = $userDetail->password;
            if (Hash::check($request->old_password, $currentPassword)) {
                $password['password'] = Hash::make($request->password);
                $userDetail->update($password);
                return response()->json([
                    'status' => 200,
                    'message' => 'password reset successful',

                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'old password not matching',

                ], 400);
            }
        }
    }

    public function getCurrentPackageDetail()
    {
        $userDetail = Auth::guard('api')->user();

        $getPurchaseData = PackagePurchaseAccountDetail::where('account_id', $userDetail?->account_id_number)->first();

        if (empty($userDetail) || empty($getPurchaseData)) {

            return response()->json([
                'status' => 400,
                'message' => 'No Package Found',

            ], 400);

        }

        $challenge = CardChallenge::find($getPurchaseData?->card_challenge_id);

        $marketDataId = array();
        foreach ($userDetail?->userMarketData as $key => $userMarketvalue) {
            array_push($marketDataId, $userMarketvalue->market_data_id);
        }
        $extraMarketData = MarketData::whereIn('id', $marketDataId)->get();

        return response()->json([
            'status' => 200,
            'message' => 'User Current Packages',
            'data' => [
                "market_data" => new MarketDataResource($userDetail->marketData),
                "challenge_data" => new CardCallangeNormalResource($challenge),
                "status" => $getPurchaseData?->account_status,
                "extra_market_data" => MarketDataResource::collection($extraMarketData),
            ],

        ], 200);

    }
}
