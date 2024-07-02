<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\HistoryReset;
use App\Models\PackagePurchaseAccountDetail;
use App\Models\RiskManagement;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function viewRiskManage(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status') ?? "In-Progress";
        $dates = $request->query('dates');

        $sort_by = $request->get('sort_by', 'id');
        $sort_order = $request->get('sort_order', 'desc');

        if ($sort_by == 'users.email') {
            $sort_by = User::select('email')->whereColumn('users.id', 'risk_management.user_id');
        } else if ($sort_by == 'users.userDetail.name') {
            $sort_by = UserDetail::selectRaw("concat(first_name,' ',last_name)")->whereColumn('user_details.user_id', 'risk_management.user_id');
        }

        $risks_manage = RiskManagement::where('account_status', $status)->with(['user' => function ($query) {
            return $query->with('userDetail');
        }, 'cardChallenge'])
            ->when($search, function ($query, $search) {
                return $query->where('trader_name', $search)
                    ->orWhere('trader_id', $search)
                    ->orWhere('account_id', $search)
                    ->orWhere('account_name', $search)
                    ->orWhere('amp_id', $search);
            })
            ->when($dates, function ($query, $dates) {
                $dates = explode("-", $dates);
                $startDate = date("Y-m-d", strtotime($dates['0']));
                $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'] . '+ 1 day')) : '');
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($search, function ($query, $search) {
                return $query->orWhereHas('user.userDetail', function ($query) use ($search) {
                    $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ")
                        ->orWhere('email', $search);
                });
            })
            ->orderBy($sort_by, $sort_order)->paginate(10)->withQueryString();

            $sort_by = $request->get('sort_by');

        return view('pages.teacher.risk-manage-view', \compact('risks_manage', 'search', 'status', 'sort_by', 'sort_order', 'dates'));
    }

    public function challengeHistories(Request $request)
    {
        $search = $request->query('search');
        $dates = $request->query('dates');
        $status = $request->query('status');
        // $sort_by = $request->get('sort_by', 'id');
        // $sort_order = $request->get('sort_order', 'desc');
        // if ($sort_by == 'users.email') {
        //     $sort_by = User::select('email')->whereColumn('users.id', 'risk_management.user_id');
        // } else if ($sort_by == 'users.userDetail.name') {
        //     $sort_by = UserDetail::selectRaw("concat(first_name,' ',last_name)")->whereColumn('user_details.user_id', 'risk_management.user_id');
        // }

        $histories = PackagePurchaseAccountDetail::with(['user' => function ($query) {
            $query->with("userDetail");
        }, 'cardChallenge'])
        ->when($search, function ($query, $search) {
            return $query->where('trader_name', $search)
                ->orWhere('trader_id', $search)
                ->orWhere('account_id', $search)
                ->orWhere('account_name', $search)
                ->orWhere('amp_id', $search);
        })
        ->when($status, function ($query) use ($status) {
            return $query->where('account_activation_status', $status == 'Live' ? 1 : 0);
        })
        ->when($dates, function ($query, $dates) {
            $dates = explode("-", $dates);
            $startDate = date("Y-m-d", strtotime($dates['0']));
            $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1']. '+ 1 day')) : '');
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->when($search, function ($query, $search) {
            return $query->orWhereHas('user.userDetail', function ($query) use ($search) {
                $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ")
                    ->orWhere('email', $search);
            });
        })
        ->orderBy('id', 'desc')->paginate(10)->withQueryString();
        // ->orderBy($sort_by, $sort_order)->paginate(10)->withQueryString();
        // $sort_by = $request->get('sort_by');

        return view('pages.teacher.historical-challenges', \compact('histories', 'status'));
    }

    // public function challengeHistories(Request $request)
    // {
    //     $search = $request->query('search');
    //     $dates = $request->query('dates');
    //     $histories = HistoryReset::with(['user' => function ($query) {
    //         return $query->with('userDetail');
    //     }, 'cardChallenge'])->when($search, function ($query, $search) {
    //         return $query->where('trader_name', $search)
    //             ->orWhere('trader_id', $search)
    //             ->orWhere('account_id', $search)
    //             ->orWhere('account_name', $search)
    //             ->orWhere('amp_id', $search)
    //             ->orWhere('account_status', $search);
    //     })
    //     ->when($dates, function ($query, $dates) {
    //         $dates = explode("-", $dates);
    //         $startDate = date("Y-m-d", strtotime($dates['0']));
    //         $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');
    //         return $query->whereBetween('created_at', [$startDate, $endDate]);
    //     })
    //     ->when($search, function ($query, $search) {
    //         return $query->orWhereHas('user.userDetail', function ($query) use ($search) {
    //             $query->whereRaw("concat(first_name,' ',last_name) like '%" . $search . "%'")
    //                     ->orWhere('email', $search);
    //         });
    //     })
    //     ->orderBy('id', 'DESC')->paginate(10)->withQueryString();

    //     return view('pages.teacher.historical-challenges', \compact('histories'));
    // }

    public function getResetHistory(Request $request, $id){
        $search = $request->query('search');
        $dates = $request->query('dates');

        $histories = HistoryReset::where('user_id', $id)
            ->when($dates, function ($query) use ($dates) {
                $dates = explode("-", $dates);
                $startDate = date("Y-m-d", strtotime($dates['0']));
                $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->orderBy('id', 'DESC')->paginate(10);

        return view('pages.teacher.reset-history', \compact('histories', 'id'));
    }

    public function getUserChallengesHistory(PackagePurchaseAccountDetail $packagePurchaseAccountDetail)
    {
        $histories = RiskManagement::where('user_id', $packagePurchaseAccountDetail->user_id)->where('account_id', $packagePurchaseAccountDetail->account_id)->first();
        return view('pages.teacher.user_challenges', \compact('histories'));
    }

    public function getRiskChallengeDetails($id) {
        $risk = RiskManagement::find($id);
        return \view('pages.teacher.risk-manage-details', \compact('risk'));
    }
}
