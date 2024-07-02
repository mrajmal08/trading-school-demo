<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function index(Request $request) {
        $search = $request->query('search');
        $faqList = Faqs::when($search, function($query) use($search) {
            $query->where('title', 'like', "%$search%");
        })->orderBy('id','Desc')->paginate(10);

        return view('pages.teacher.faq.faq-list',compact(['faqList']));
    }

    public function create(){
        return view('pages.teacher.faq.faq-create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $row = Faqs::updateOrCreate($validator->validated());

        if ($row) {
            return Redirect::route('teacher.faq.index')->with(['success' => 'Faq create successfully']);
        }
    }

    public function edit($id){
        $row = Faqs::findOrFail($id);
        return view('pages.teacher.faq.faq-edit',compact(['row']));
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $data = Faqs::updateOrCreate(['id' => $id], $validator->validated());

        if ($data) {
            return Redirect::route('teacher.faq.index')->with(['success' => 'Faq update successfully']);
        }
    }

    public function destroy($id)
    {
        $faq = Faqs::findOrFail($id);
        if ($faq->delete()){
            return Redirect::route('teacher.faq.index')->with(['success'=>'Faq deleted successfully']);
        }
    }
}
