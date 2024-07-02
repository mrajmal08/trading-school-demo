<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FrontEnd\WebsettingResource;
use App\Models\WebSetting;

class FrontEndController extends Controller
{
    public function webSetting()
    {
        $webSetting = WebSetting::first();

        $data = new WebsettingResource($webSetting);
        return response()->json([

            'status' => 200,
            'message' => "Web Settings",
            'data' => $data,
        ], 200);

        // if (empty($webSetting)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //         'data' => "",
        //     ], 400);
        // } else {

        //     $data = new WebsettingResource($webSetting);
        //     return response()->json([

        //         'status' => 200,
        //         'message' => "Web Settings",
        //         'data' => $data,
        //     ], 200);

        // }

    }
    public function webSettingPrivacyPolicy()
    {
        $webSetting = WebSetting::first();

        $data = $webSetting->privacy_policy_detail;
        return response()->json([

            'status' => 200,
            'message' => "Web Settings Privacy Policy",
            'data' => $data,
        ], 200);

        // if (empty($webSetting)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //     ], 400);
        // } else {

        //     $data = $webSetting->privacy_policy_detail;
        //     return response()->json([

        //         'status' => 200,
        //         'message' => "Web Settings Privacy Policy",
        //         'data' => $data,
        //     ], 200);

        // }

    }

    public function webSettingTermsService()
    {
        $webSetting = WebSetting::first();

        $data = $webSetting->terms_service_detail;
        return response()->json([

            'status' => 200,
            'message' => "Web Settings Terms Service",
            'data' => $data,
        ], 200);

        // if (empty($webSetting)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //     ], 400);
        // } else {

        //     $data = $webSetting->terms_service_detail;
        //     return response()->json([

        //         'status' => 200,
        //         'message' => "Web Settings Terms Service",
        //         'data' => $data,
        //     ], 200);

        // }

    }
}
