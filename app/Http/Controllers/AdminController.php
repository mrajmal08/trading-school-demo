<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Teacher;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
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

        $countryList = Country::all();
        $allTeacher = Teacher::
            when($search, fn($query) =>
            $query->orwhere('email', 'like', "%$search%")
                ->orWhereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ")
                ->orwhere('organisation', 'like', "%$search%")->orwhere('designation', 'like', "%$search%"))

            ->when($country, function ($query) use ($country) {
                $query->where('country', 'like', "%$country%");
            })
            ->when(!empty($endDate), function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })->orderBy('id', 'DESC')->paginate(10);
        return view('pages.admin.admin-dashboard', compact(['allTeacher', 'countryList']));
    }

    public function searchEducator(Request $request)
    {
        $search = $request->search;
        $country = $request->country;

        $dates = explode("-", $request->query('dates'));
        $startDate = date("Y-m-d", strtotime($dates['0']));
        $endDate = (isset($dates['1']) ? date("Y-m-d", strtotime($dates['1'])) : '');
        $countryList = Country::all();
        // $allTeacher = Teacher::all
        $getTeacher = Teacher::latest();

        if ($request->has('search')) {
            $getTeacher->where('email', 'like', "%$search%")
                ->orwhere('first_name', 'like', "%$search%")
                ->orwhere('last_name', 'like', "%$search%")
                ->orwhere('organisation', 'like', "%$search%")
                ->orwhere('designation', 'like', "%$search%");
        }

        if ($request->has('dates')) {
            $getTeacher->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($request->has('country')) {
            // dd($getTeacher);
            $getTeacher->where('country', $country);
        }
        $allTeacher = $getTeacher->paginate(10);
        return view('pages.admin.admin-dashboard', compact(['allTeacher', 'countryList']));
    }

}
