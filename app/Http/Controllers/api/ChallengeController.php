<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Challenges\CardChallengeResource;
use App\Http\Resources\Challenges\ServiceResource;
use App\Models\CardChallenge;
use App\Models\Service;

// use App\Models\CardChallenge;

class ChallengeController extends Controller
{
    public function challenges()
    {
        $allCards = CardChallenge::all();

        return response()->json([
            'status' => 200,
            'message' => "All Challenges",
            'data' => CardChallengeResource::collection($allCards),
        ], 200);
    }

    public function serviceChallenges(Service $service)
    {
        $allCards = CardChallenge::where('service_id', $service->id)->get()->collect();
        return response()->json([
            'status' => 200,
            'message' => "All Challenges",
            'data' => CardChallengeResource::collection($allCards),
        ], 200);

    }

    public function challengeDetails(CardChallenge $cardChallenge)
    {
        $allCards = $cardChallenge;

        return response()->json([
            'status' => 200,
            'message' => "All Challenges",
            'data' => new CardChallengeResource($allCards),
        ], 200);

    }

    public function allServices()
    {
        $allServices = Service::all();

        return response()->json([
            'status' => 200,
            'message' => "All Challenges",
            'data' => ServiceResource::collection($allServices),
        ], 200);

    }

}
