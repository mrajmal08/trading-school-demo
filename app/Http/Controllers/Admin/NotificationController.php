<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewUserRegistration;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function create()
    {
        $userList = User::with('userDetail')->get();
        return view('pages.admin.notification-create', compact('userList'));
    }

    public function store(Request $request)
    {
        // $user = User::find(auth()->user()->id);
        // \dd($request->user);
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'head_title' => 'required',
            'message' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $user = Admin::find(auth()->user()->id);
        $user->addMedia($request->file('image'), 's3')->toMediaCollection();
        $mediaItem = $user->getMedia()->first();
        $data['image'] = $mediaItem['original_url'];
        $data['head_title'] = $request->head_title;
        $data['message'] = $request->message;
        $data['date'] = $request->date;

        if ($request->user[0] === 'all') {
            $data['users'] = $request->user[0];
        } else {
            $data['users'] = $request->user;
        }

        $result = event(new NewUserRegistration($data));

        $url = 'https://fcm.googleapis.com/fcm/send';

        if ($request->user[0] == 'all') {
            $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        } else {
            $FcmToken = User::whereIn('id', $data['users'])->pluck('device_token')->all();
        }

        // $serverKey = 'AAAAs3By09A:APA91bEs75EkBPV0BpNyMIvCnUI1fQAlaxqz7-SD6Qeljr3-gC80TeG1ULw_sHi6xB4N_cL7ENk-e2J0A08oIE0aDGZuev9Bd1ilui5RhbQs7l7ZQi-aN88CFXMhJybmVm_AArWkVBwp'; // ADD SERVER KEY HERE PROVIDED BY FCM
        $serverKey = config('app.fire_base_key');
        $message = array(
            $data['message'],
            $data['image'],
            $data['date'],

        );
        $pushdata = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $data['head_title'],
                "body" => $message,
                "icon" => $data['image'],

            ],
        ];
        $encodedData = json_encode($pushdata);

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
        $nresult = curl_exec($ch);
        if ($nresult === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        // dd($result);

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Push Notification Send',
        //     'data' => $nresult,

        // ], 200);

        if ($result) {
            return Redirect::route('notification.create')->with(['success' => 'Notification send successfully']);
        }
    }
}
