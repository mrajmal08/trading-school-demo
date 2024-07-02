<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $country = $request->query('country');

        $dates = explode("-", $request->query('dates'));
        $startDate = date("Y-m-d", strtotime($dates['0']));
        $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');

        $users = User::with('userDetail','allTrades')
        ->when($search, fn($query) => $query->orwhere('email', 'like', "%$search%")->orwhere('account_name', 'like', "%$search%"))

        ->when($search, function ($query) use ($search) {
            return $query->orwhereHas('userDetail', function ($query) use ($search) {
                $query->WhereRaw("concat(first_name, ' ', last_name) like '%" .$search. "%' ");;
            });
        })
            ->when(!empty($endDate), function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })->paginate(10);
        return view('pages.dashboard',compact(['users']));
    }

}
