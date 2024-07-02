<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\Country;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Faqs;
use App\Mail\TeacherCreateMail;
use App\Http\Controllers\Controller;
use App\Mail\SendTeacherCreateMail;
use App\Rules\StrongPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $allTeacher = Teacher::all();
//        return view('pages.teacher-list',compact('allTeacher'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countryList = Country::all();
        $stateList = State::all();
        return view('pages.admin.teacher-create',compact(['countryList','stateList']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email'=> 'required|email|unique:teachers,email',
            'country' => 'nullable',
            'state' => 'nullable',
            'organisation' => 'nullable',
            'designation' => 'nullable',
            'password' => ['required', 'confirmed', new StrongPassword],
            'password_confirmation' => ['required', 'same:password', new StrongPassword],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($request->password);
        $data = Teacher::create($data);

        $mailData = [
            'name'=> $request->first_name.' '.$request->last_name,
            'email'=> $request->email,
            'password'=> $request->password,
            'url' => route('login'),
        ];

        if ($data) {
            Mail::to($request->email)->send(new SendTeacherCreateMail($mailData));
            return Redirect::route('admin.staff')->with(['success'=>'Teacher created successfully']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $request->all();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countryList = Country::all();
        $row = Teacher::findOrFail($id);
        return view('pages.admin.teacher-edit',compact(['row','countryList']));
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
            'password' => ['nullable', 'confirmed', new StrongPassword],
            'password_confirmation' => ['nullable', 'same:password', new StrongPassword],
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
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
            return Redirect::route('admin.staff')->with(['success'=>'Teacher update successfully']);
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
            return Redirect::route('admin.staff')->with(['success'=>'Teacher deleted successfully']);
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
