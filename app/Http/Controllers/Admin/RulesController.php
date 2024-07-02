<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RulesController extends Controller
{
    public function index(Request $request){
        $search = $request->query('search');
        $rulesList = Rules::when($search, function($query) use($search) {
            $query->where('title', 'like', "%$search%");
        })->orderBy('id','Desc')->paginate(10);
        return view('pages.admin.rules.rules-list',compact(['rulesList']));
    }

    public function create(){
        return view('pages.admin.rules.rules-create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $row = Rules::updateOrCreate($validator->validated());

        if ($row) {
            return Redirect::route('rules.index')->with(['success' => 'Rules create successfully']);
        }
    }

    public function edit($id){
        $row = Rules::findOrFail($id);
        return view('pages.admin.rules.rules-edit',compact(['row']));
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $data = Rules::updateOrCreate(['id' => $id], $validator->validated());

        if ($data) {
            return Redirect::route('rules.index')->with(['success' => 'Rules update successfully']);
        }
    }

    public function destroy($id)
    {
        $rule = Rules::findOrFail($id);
        if ($rule->delete()){
            return Redirect::route('rules.index')->with(['success'=>'Rules deleted successfully']);
        }
    }
}
