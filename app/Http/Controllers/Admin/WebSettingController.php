<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class WebSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $row = WebSetting::all()->first();
        if (!empty($row)) {
            return Redirect::route('web.setting.edit', $row->id);
        } else {
            return Redirect::route('web.setting.create');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pages.admin.web-setting-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'footer_logo_description' => 'required',
            'term_of_service' => 'required',
            'privacy_policy' => 'required',
            'site_footer_copyright' => 'required',
            'subscribe_title' => 'required',
            'subscribe_description' => 'required',
            'linkedin' => 'required',
            'instagram' => 'required',
            'facebook' => 'required',
            'privacy_policy_detail' => 'required',
            'terms_service_detail' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->validated();

        $row = WebSetting::updateOrCreate($data);

        if ($request->hasFile('logo')) {
            $row->clearMediaCollection('logo');
            $row->addMedia($request->file('logo'), 's3')->toMediaCollection('logo');
            $data['logo'] = $row->getFirstMediaUrl('logo');
        }
        if ($request->hasFile('dark_logo')) {
            $row->clearMediaCollection('dark_logo');
            $row->addMedia($request->file('dark_logo'), 's3')->toMediaCollection('dark_logo');
            $data['dark_logo'] = $row->getFirstMediaUrl('dark_logo');
        }
        WebSetting::where('id', $row->id)->update(['logo' => $data['logo'], 'dark_logo' => $data['dark_logo']]);
        if ($row) {
            return Redirect::route('web.setting.edit', $row->id)->with(['success' => 'Setting update successfully']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $row = WebSetting::where('id', $id)->first();
        return view('pages.admin.web-setting-create', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $row = WebSetting::where('id', $id)->first();
        return view('pages.admin.web-setting-edit', compact('row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $row = WebSetting::find($id);

        $validator = Validator::make($request->all(), [
            'footer_logo_description' => 'required',
            'term_of_service' => 'required',
            'privacy_policy' => 'required',
            'site_footer_copyright' => 'required',
            'subscribe_title' => 'required',
            'subscribe_description' => 'required',
            'linkedin' => 'required',
            'instagram' => 'required',
            'facebook' => 'required',
            'privacy_policy_detail' => 'required',
            'terms_service_detail' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->validated();

        if ($request->hasFile('logo')) {
            $row->clearMediaCollection('logo');
            $row->addMedia($request->file('logo'), 's3')->toMediaCollection('logo');
            $data['logo'] = $row->getFirstMediaUrl('logo');
        }
        if ($request->hasFile('dark_logo')) {
            $row->clearMediaCollection('dark_logo');
            $row->addMedia($request->file('dark_logo'), 's3')->toMediaCollection('dark_logo');
            $data['dark_logo'] = $row->getFirstMediaUrl('dark_logo');
        }

        $result = WebSetting::updateOrCreate(['id' => $id], $data);

        if ($result) {
            return Redirect::route('web.setting.edit', $id)->with(['success' => 'Setting update successfully']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
