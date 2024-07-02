<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        $userDetail = Auth::guard('api')->user();

        // $user = User::findOrFail($userDetail->id);
        $user = User::find($userDetail->id);
        $user->device_token = $request->token;
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Token successfully stored.',

        ], 200);

    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        // $serverKey = 'AAAAs3By09A:APA91bEs75EkBPV0BpNyMIvCnUI1fQAlaxqz7-SD6Qeljr3-gC80TeG1ULw_sHi6xB4N_cL7ENk-e2J0A08oIE0aDGZuev9Bd1ilui5RhbQs7l7ZQi-aN88CFXMhJybmVm_AArWkVBwp'; // ADD SERVER KEY HERE PROVIDED BY FCM
        $serverKey = config('app.fire_base_key');
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ],
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        // dd($result);

        return response()->json([
            'status' => 200,
            'message' => 'Push Notification Send',
            'data' => $result,

        ], 200);
    }

}
