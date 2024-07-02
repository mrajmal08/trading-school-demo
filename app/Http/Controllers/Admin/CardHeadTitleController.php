<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CardHeadTitle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CardHeadTitleController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $cardHeadTitleList = CardHeadTitle::when($search, function($query) use($search) {
            $query->orwhere('title', 'like', "%$search%")->orwhere('type', 'like', "%$search%");
        })->paginate(10);
        return view("pages.admin.card-head-title.card-head-title-list",compact('cardHeadTitleList'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view("pages.admin.card-head-title.card-head-title-create");
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $row = CardHeadTitle::updateOrCreate($validator->validated());

        if ($row) {
            return Redirect::route('card-head-title.index')->with(['success' => 'Card head title create successfully']);
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(CardHeadTitle $cardHeadTitle)
    {
        return view("pages.admin.card-head-title.card-head-title-edit",compact('cardHeadTitle'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, CardHeadTitle $cardHeadTitle)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $row = $cardHeadTitle->update($validator->validated());

        if ($row) {
            return Redirect::route('card-head-title.index')->with(['success' => 'Card head title update successfully']);
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(CardHeadTitle $cardHeadTitle)
    {
        if ($cardHeadTitle->delete()){
            return ['success'=>'Card head title deleted successfully'];
        }
    }
}
