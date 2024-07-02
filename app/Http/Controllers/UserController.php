<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AllTrade;
use App\Models\TradeRecord;
use App\Models\GraphHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\PackagePurchaseAccountDetail;

class UserController extends Controller
{
    public function index()
    {
        $allUser = User::with('userDetail')->get();
        return view('pages.user-list', compact('allUser'));
    }

    public function show(Request $request, User $user)
    {
        $userDetail = PackagePurchaseAccountDetail::where('user_id', $user->id)->get()->first();

        $dashBoardUrl = config('app.go_lang_url');

        $accountID = ($userDetail)?$userDetail->account_id:'';
        $uuid = ($userDetail)?$userDetail->uuid:'';
        $tradeID = ($userDetail)?$userDetail->trader_id:'';
        $ampID = ($userDetail)?$userDetail->amp_id:'';
        $acountName = ($userDetail)?$userDetail->account_name:'';

        $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $accountID . "/" . $tradeID . "/" . $acountName;
        $response = Http::get($dashBoardUrl)->object();
        if(!empty($response)){
            if ($response->status === 'fail') {
                return redirect()->back()->with(['status' => 'info', 'message' => 'Sorry, Fail to fetch the data.']);
            }

            $response = $response->data;
        }else{
            $response = (object) [
                "progress" => [],
                "chartHistory" => [],
                "tradeHistory" => [],
                "allTrades" => [],
                "profitTrades" => [],
                "losingTrades" => []
            ];
        }

        $allTrade = $response->allTrades;
        $tradeRecord = $response->tradeHistory;
        $graphHistory = collect($response->chartHistory);

        $winningRate = !empty((array)$response->profitTrades)? $response->profitTrades->percentProfitable : "0";
        $losingrate = !empty((array)$response->losingTrades)? $response->profitTrades->percentProfitable : "0";

        return view('pages.matrix', compact(['tradeRecord', 'allTrade', 'graphHistory','winningRate','losingrate']));
    }
}
