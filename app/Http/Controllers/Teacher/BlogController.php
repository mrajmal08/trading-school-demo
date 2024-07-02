<?php

namespace App\Http\Controllers\Teacher;

use App\Models\BlogDetail;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{

    public function index(Request $request){
        $search = $request->query('search');
        $blogList = BlogDetail::
        when($search, function($query) use($search) {
            $query->orwhere('title', 'like', "%$search%");
            $query->orwhere('user_name', 'like', "%$search%");
        })->orderBy('id','desc')->paginate(10);
        return view('pages.teacher.blog-list',compact('blogList'));
    }

    public function create(){
        $tags = Tags::get();
        return view('pages.teacher.blog-create',compact('tags'));
    }


     /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'title' => 'required',
            'detail' => 'required',
            'date' => 'required',
            'publish' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        // dd($validator->validated());
        $data = $validator->validated();

        if($request->tags){
            $data['tags'] = implode(',', $request->tags);
        }

        $tags = [];
        foreach(Tags::get()->toArray() as $tag){
            $tags[] = $tag['name'];
        }
        if ($request->tags){
            $diffTag = array_diff($request->tags, $tags);
            $tagData = [];
            foreach($diffTag as $tag){
                $tagData[] = ['name' => $tag];
            }
            Tags::insert($tagData);
        }

        $row = BlogDetail::updateOrCreate($data);

        if ($request->hasFile('user_image')) {
            $row->clearMediaCollection('user_image');
            $row->addMedia($request->file('user_image'), 's3')->toMediaCollection('user_image');
            $data['user_image'] = $row->getFirstMediaUrl('user_image');
            BlogDetail::where('id', $row->id)->update(['user_image' => $data['user_image']]);
        }
        if ($request->hasFile('picture')) {
            $row->clearMediaCollection('picture');
            $row->addMedia($request->file('picture'), 's3')->toMediaCollection('picture');
            $data['picture'] = $row->getFirstMediaUrl('picture');
            BlogDetail::where('id', $row->id)->update(['picture' => $data['picture']]);
        }

        if ($row) {
            return Redirect::route('teacher.blog.index', $row->id)->with(['success' => 'Blog create successfully']);
        }
    }


    public function edit($id){
        $tags = Tags::get();
        $row = BlogDetail::findOrFail($id);
        return view('pages.teacher.blog-edit',compact(['row','tags']));
    }


    public function update(Request $request, $id){

        $row = BlogDetail::find($id);

        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'title' => 'required',
            'detail' => 'required',
            'picture' => 'nullable',
            'date' => 'required',
            // 'tags' => 'required',
            'user_image' => 'nullable',
            'publish' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->validated();

        if($request->tags){
            $data['tags'] = implode(',', $request->tags);
        }

        $tags = [];
        foreach(Tags::get()->toArray() as $tag){
            $tags[] = $tag['name'];
        }
        if ($request->tags){
            $diffTag = array_diff($request->tags, $tags);
            $tagData = [];
            foreach($diffTag as $tag){
                $tagData[] = ['name' => $tag];
            }
            Tags::insert($tagData);
        }

        if ($request->hasFile('user_image')) {
            $row->clearMediaCollection('user_image');
            $row->addMedia($request->file('user_image'), 's3')->toMediaCollection('user_image');
            $data['user_image'] = $row->getFirstMediaUrl('user_image');
        }
        if ($request->hasFile('picture')) {
            $row->clearMediaCollection('picture');
            $row->addMedia($request->file('picture'), 's3')->toMediaCollection('picture');
            $data['picture'] = $row->getFirstMediaUrl('picture');
        }

        $result = BlogDetail::updateOrCreate(['id' => $id], $data);

        if ($result) {
            return Redirect::route('teacher.blog.index', $row->id)->with(['success' => 'Blog update successfully']);
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
        $blog = BlogDetail::findOrFail($id);
        if ($blog->delete()){
            return Redirect::route('teacher.blog.index')->with(['success'=>'Blog deleted successfully']);
        }
    }


    public function tags(){
        return Tags::get();
    }
}
