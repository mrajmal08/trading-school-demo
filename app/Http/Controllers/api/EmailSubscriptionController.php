<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\EmailSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailSubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['bail', 'required', 'string', 'email', 'max:255', 'unique:email_subscriptions'],
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

        $data = EmailSubscription::create($validator->validated());

        return response()->json([
            'status' => 201,
            'message' => 'Subscription creation successfully',
            'data' => $data->email,
        ], 201);
    }
}
