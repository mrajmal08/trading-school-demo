<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\State;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $country = $request->query('country');

        $dates = explode("-", $request->query('dates'));
        $startDate = date("Y-m-d", strtotime($dates['0']));
        $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');
        $countryList = Country::all();
        $allUser = User::with(['userDetail', 'packagePurchaseAccountDetail' => function ($q) {
            $q->with(['cardChallenge' => function ($q) {
                $q->with('service');
            }]);
        }])
        ->when($search, function ($query) use ($search) {
            return $query->where("email", $search);
        })
        ->when($search, function ($query) use ($search) {
            return $query->orWhere("account_name", $search);
        })
        ->when($search, function ($query) use ($search) {
            return $query->orWhereHas('userDetail', function ($query) use ($search) {
                $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ");
            });
        })
        ->when($search, function ($query) use ($search) {
            return $query->orWhereHas('packagePurchaseAccountDetail', function ($query) use ($search) {
                $query->where('trader_name', $search);
            });
        })
        ->when($search, function ($query) use ($search) {
            return $query->orWhereHas('packagePurchaseAccountDetail', function ($query) use ($search) {
                $query->where('account_status', $search);
            });
        })
        ->when($search, function($query) use ($search) {
            return $query->orWhereHas("packagePurchaseAccountDetail.cardChallenge", function($query) use($search) {
                $query->where('title', $search);
            });
        })
        ->when(!empty($endDate), function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->orderBy('id', 'DESC')
        ->paginate(10)->withQueryString();

            // ->when($search, fn($query) => $query->orwhere('email', 'like', "%$search%")->orwhere('account_name', 'like', "%$search%"))
            // ->when($search, function ($query) use ($search) {
            //     return $query->orwhereHas('userDetail', function ($query) use ($search) {
            //         $query->WhereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ");
            //     });
            // })

            // ->when($country, function ($query) use ($country) {
            //     return $query->orwhereHas('userDetail', function ($query) use ($country) {
            //         $query->where('country', 'like', "%$country%");
            //     });
            // })
            // ->when(!empty($endDate), function ($query) use ($startDate, $endDate) {
            //     return $query->whereBetween('created_at', [$startDate, $endDate]);
            // })->orderBy('id', 'DESC')
            // ->paginate(10);

        return view('pages.teacher.user-list', compact(['allUser', 'countryList']));
    }

    public function edit($id)
    {
        $countryList = Country::all();
        $row = User::with('userDetail')->find($id);
        return view('pages.teacher.user-edit', compact(['row', 'countryList']));
    }

    public function update(Request $request, $id)
    {
        $userId = User::find($id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required|string',
            'age' => 'required',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $upDateUserDetail = UserDetail::where('user_id', $id)->first();
        $updateData = $validator->validated();
        $dob = Carbon::createFromFormat('Y-m-d', $request->age);
        $updateData['age'] = $dob;
        $updateData['state'] = $request->state;
        if ($request->hasFile('picture')) {
            $userId->clearMediaCollection();
            $userId->addMedia($request->file('picture'), 's3')->toMediaCollection();
            $mediaItem = $userId->getMedia()->first();
            $updateData['picture'] = $mediaItem['original_url'];
        }
        $upDateUserDetail->update($updateData);
        if ($upDateUserDetail) {
            return Redirect::route('teacher.trader.list')->with(['success' => 'User update successfully']);
        }
    }

    public function show(User $user, Request $request)
    {
        // dd($request->filter);
        $userDetail = PackagePurchaseAccountDetail::where('user_id', $user->id)->orderBy('id', 'DESC')->first();

        $dashBoardUrl = config('app.go_lang_url');

        $accountID = ($userDetail) ? $userDetail->account_id : '';
        $uuid = ($userDetail) ? $userDetail->uuid : '';
        $tradeID = ($userDetail) ? $userDetail->trader_id : '';
        $ampID = ($userDetail) ? $userDetail->amp_id : '';
        $accountName = ($userDetail) ? $userDetail->account_name : '';
        $package_status = ($userDetail) ? $userDetail->account_status : '';

        if (empty($accountID) || empty($tradeID) || empty($accountName)) {
            // return redirect()->back()->with(['status' => 'info', 'message' => 'You do not purchase any challenges yet']);
        }

        if ($request->filter === "0") {
            $startDate = Carbon::now()->subtract('month', 1);
            $endDate = Carbon::now()->addDay();
            $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $accountID . "/" . $tradeID . "/" . $accountName . "?startDate=" . $startDate . "&endDate=" . $endDate;
        } else {
            $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $accountID . "/" . $tradeID . "/" . $accountName;
        }

        $response = Http::get($dashBoardUrl)->object();

        if (!empty($response)) {
            if ($response->status == 'fail') {
                return redirect()->back()->with(['status' => 'info', 'message' => 'Sorry, Fail to fetch the data. Account processing']);
            } else if ($response->status == '404') {
                return redirect()->back()->with(['status' => 'info', 'message' => $response->message]);
            }
            $response = $response->data;
        } else {
            $response = (object) [
                "progress" => [],
                "chartHistory" => [],
                "positions" => [],
                "tradeHistory" => [],
                "allTrades" => (object) [],
                "profitTrades" => [],
                "losingTrades" => [],
                "profitTarget" => 0,
            ];
        }

        $allTrade = $response?->allTrades ?? 0;

        $profitTrades = $response?->profitTrades ?? 0;
        $losingTrades = $response?->losingTrades ?? 0;

        $livePositions = $response?->positions ?? 0;
        $tradeRecord = $response?->tradeHistory ?? 0;
        $graphHistory = collect($response?->chartHistory);
        $profit_target = $response?->profitTarget ?? 0;

        $progress = array(
            'minimum_days' => $response?->minimumDays ?? 0,
            'account_size' => $response?->accountSize ?? 0, //starting balance
            'balance' => $response?->balance ?? 0, //Account Balance
            'net_liq_value' => $response?->netLiqValue ?? 0, // -$43
            'trading_day' => (int) (!empty($response?->tradingDay)) ? $response?->tradingDay : 0, //Trading day
            'sodbalance' => $response?->sodbalance ?? 0,

            'rule_one_fail' => $response?->rule1Failed ?? 0, //bool
            'rule_one_enable' => $response?->rule1Enabled ?? 0, //40
            'rule_one_value' => $response?->rule1Value ?? 0, //40
            'rule_one_key' => $response?->rule1Key ?? 0, //60
            'rule_one_maximum' => $response?->rule1Maximum ?? 0, //60

            'rule_two_fail' => $response?->rule2Failed ?? 0, //bool
            'rule_two_enable' => $response?->rule2Enabled ?? 0, //40
            'rule_two_value' => $response?->rule2Value ?? 0, //960
            'rule_two_key' => $response?->rule2Key ?? 0, //5000
            'rule_two_maximum' => $response?->rule2Maximum ?? 0, // 5000

            'rule_three_fail' => $response?->rule3Failed ?? 0, //bool
            'rule_three_enable' => $response?->rule3Enabled ?? 0,
            'rule_three_value' => $response?->rule3Value ?? 0, //960
            'rule_three_key' => $response?->rule3Key ?? 0, //5000
            'rule_three_maximum' => $response?->rule3Maximum ?? 0, // 5000
        );
        // \dd($graphHistory);
        // $winningRate = !empty((array) $response->profitTrades) ? $response->profitTrades->percentProfitable : 0;
        // $losingrate = !empty((array) $response->losingTrades) ? $response->profitTrades->percentProfitable : 0;
        // return view('pages.admin.matrix', compact(['user', 'profit_target', 'progress', 'tradeRecord', 'livePositions', 'allTrade', 'profitTrades', 'losingTrades', 'graphHistory', 'winningRate', 'losingrate']));
        return view('pages.teacher.matrix', compact(['user', 'profit_target', 'progress', 'tradeRecord', 'livePositions', 'allTrade', 'profitTrades', 'losingTrades', 'graphHistory', 'userDetail']));
    }

    public function balance(Request $request)
    {
        $user = User::find($request->userId);

        $dashBoardUrl = config('app.go_lang_url');

        $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $user->account_id_number . "/" . $user->traderid . "/" . $user->account_name;

        $response = Http::get($dashBoardUrl);
        // \dd($response->object()->accountName);
        if (($response->successful() == false) || ($response->failed() == true)) {
            return response()->json([
                'account_name' => $user->account_name,
                'balance' => 0,
            ]);
        }

        if (($response->successful() == true) && ($response->failed() == false) && (!empty($response->collect()))) {
            $goLangDbValue = $response->object();
            // \dd($goLangDbValue->data->accountName);
            return response()->json([
                'account_name' => $goLangDbValue->data->accountName,
                'balance' => $goLangDbValue->data->balance,
            ]);

        }
    }

    public function ajaxMatrix($packagePurchaseAccId, $range)
    {
        $packageDetail = PackagePurchaseAccountDetail::find($packagePurchaseAccId);
        $userDetail = User::find($packageDetail->user_id);

        if ($range == 'all') {
            $startData = $userDetail?->userDetail?->start_date;
            $startDate = Carbon::parse($startData);
            $endDate = Carbon::now()->addDays(1);
        }

        if ($range == 1) {
            $startDate = Carbon::now()->subtract('week', 1);
            $endDate = Carbon::now()->addDay();

        }

        if ($range == 2) {
            $startDate = Carbon::now()->subtract('week', 2);
            $endDate = Carbon::now()->addDay();

        }

        if ($range == 3) {
            $startDate = Carbon::now()->subtract('week', 3);
            $endDate = Carbon::now()->addDay();

        }

        if ($range == 4) {
            $startDate = Carbon::now()->subtract('week', 4);
            $endDate = Carbon::now()->addDay();
        }

        $dashBoardUrl = config('app.go_lang_url');

        $accountID = ($packageDetail) ? $packageDetail->account_id : '';
        $uuid = ($packageDetail) ? $packageDetail->uuid : '';
        $tradeID = ($packageDetail) ? $packageDetail->trader_id : '';
        $ampID = ($packageDetail) ? $packageDetail->amp_id : '';
        $accountName = ($packageDetail) ? $packageDetail->account_name : '';
        $package_status = ($packageDetail) ? $packageDetail->account_status : '';

        $dashBoardUrl = $dashBoardUrl . "futures/dashboard/history/" . $accountID . "/" . $tradeID . "/" . $accountName . "?startDate=" . $startDate . "&endDate=" . $endDate;
        $response = Http::get($dashBoardUrl)->object();

        if (!empty($response)) {
            if ($response->status == 'fail') {
                return redirect()->back()->with(['status' => 'info', 'message' => 'Sorry, Fail to fetch the data. Account processing']);
            }
            $response = $response->data;
        } else {
            $response = (object) [
                "progress" => [],
                "chartHistory" => [],
                "positions" => [],
                "tradeHistory" => [],
                "allTrades" => (object) [],
                "profitTrades" => [],
                "losingTrades" => [],
                "profitTarget" => 0,
            ];
        }
        return response()->json($response);

    }

    public function stateList(Request $request)
    {
        return State::where('country_id', $request->countryId)->get();
    }
}
