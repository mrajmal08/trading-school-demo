<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Country\CountryResource;
use App\Models\Country;

class CountryStateController extends Controller
{
    public function country()
    {
        $country = Country::all();

        $data = CountryResource::collection($country);
        return response()->json([

            'status' => 200,
            'message' => "Country List",
            'data' => $data,
        ], 200);

        // if (empty($country)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //         'data' => "",
        //     ], 400);
        // } else {

        //     $data = CountryResource::collection($country);
        //     return response()->json([

        //         'status' => 200,
        //         'message' => "Country List",
        //         'data' => $data,
        //     ], 200);

        // }

    }
}
