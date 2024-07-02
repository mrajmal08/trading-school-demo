<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CardHeadTitle;
use App\Models\CardSubHeadTitle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CardSubHeadTitleController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $cardSubHeadTitleList = CardSubHeadTitle::with('cardHeadTitle')->when($search, function($query) use($search) {
            $query->orwhere('title', 'like', "%$search%");
        })->when($search, function ($query) use ($search) {
            return $query->orwhereHas('cardHeadTitle', function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
            });
        })->paginate(10);
        return view("pages.admin.card-sub-head-title.card-sub-head-title-list",compact('cardSubHeadTitleList'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $cardHeadTitle = CardHeadTitle::get();
        return view("pages.admin.card-sub-head-title.card-sub-head-title-create",compact('cardHeadTitle'));
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
            'card_head_title_id' => 'required',
            'value' => 'required',
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $row = CardSubHeadTitle::updateOrCreate($validator->validated());

        if ($row) {
            return Redirect::route('card-sub-head-title.index')->with(['success' => 'Card sub head title create successfully']);
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(CardSubHeadTitle $cardSubHeadTitle)
    {
        $cardHeadTitle = CardHeadTitle::get();
        return view("pages.admin.card-sub-head-title.card-sub-head-title-edit",compact(['cardSubHeadTitle','cardHeadTitle']));
    }

     /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, CardSubHeadTitle $cardSubHeadTitle)
    {
        $validator = Validator::make($request->all(), [
            'card_head_title_id' => 'required',
            'value' => 'required',
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $row = $cardSubHeadTitle->update($validator->validated());

        if ($row) {
            return Redirect::route('card-sub-head-title.index')->with(['success' => 'Card sub head title update successfully']);
        }
    }

     /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(CardSubHeadTitle $cardSubHeadTitle)
    {
        if ($cardSubHeadTitle->delete()){
            return ['success'=>'Card sub head title deleted successfully'];
        }
    }

    public function get_sub_titles($title_id)
    {
        $sub_titles = CardSubHeadTitle::where("card_head_title_id", $title_id)->get();
        return \response()->json($sub_titles, 200);
    }
}
