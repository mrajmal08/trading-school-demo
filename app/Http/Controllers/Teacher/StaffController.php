<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
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
        return view('pages.teacher.staff-list', compact(['allTeacher', 'countryList']));
    }

    public function create()
    {
        $countryList = Country::all();
        $stateList = State::all();
        return view('pages.teacher.teacher-create',compact(['countryList','stateList']));
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
            $getTeacher->where('country', $country);
        }

        $allTeacher = $getTeacher->paginate(10);
        return view('pages.teacher.staff-list', compact(['allTeacher', 'countryList']));
    }

    public function edit($id)
    {
        $countryList = Country::all();
        $row = Teacher::findOrFail($id);
        return view('pages.teacher.teacher-edit',compact(['row','countryList']));
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email'=> 'required|email',
            'country' => 'nullable',
            'state' => 'nullable',
            'organisation' => 'nullable',
            'designation' => 'nullable',
            'password' => 'nullable',
            'password_confirmation' => 'nullable|same:password',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return \redirect()->back()->withErrors($validator);
        }

        $data = $validator->validated();
        
        if($request->password == null){
            unset($data['password']);
            unset($data['password_confirmation']);
        }else{
            $data['password'] = Hash::make($request->password);
        }

        $result = Teacher::updateOrCreate(['id' => $id], $data);
        if ($result) {
            return \redirect()->route('teacher.staff')->with(['success'=>'Teacher update successfully']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        if ($teacher->delete()){
            return \redirect()->route('staffs')->with(['success'=>'Teacher deleted successfully']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function stateList(Request $request)
    {
        return State::where('country_id', $request->countryId)->get();
    }
}
