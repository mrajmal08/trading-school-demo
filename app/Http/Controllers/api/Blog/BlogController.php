<?php

namespace App\Http\Controllers\api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogDetailResource;
use App\Models\BlogDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// use App\Http\Resources\Blog\BlogCategoryResource;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = BlogDetail::where('publish',1)->orderBy('id', 'desc')->paginate(10);

        if (empty($blogs)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $data = BlogDetailResource::collection($blogs)->resource;
            return response()->json([

                'status' => 200,
                'message' => "Blog List",
                'data' => $data,
            ], 200);

        }

    }

    public function show(BlogDetail $blogdetail)
    {
        $blogs = $blogdetail;

        if (empty($blogs)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $data = new BlogDetailResource($blogs);
            return response()->json([

                'status' => 200,
                'message' => "Blog Detail",
                'data' => $data,
            ], 200);

        }

    }

    public function search(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->toArray() as $key => $value) {

                foreach ($value as $key => $errorvalue) {
                    $errorMessage = $errorvalue;
                    break;
                }

                break;
            }

            return response()->json([
                'status' => 400,
                'message' => $errorMessage,

            ], 400);
        }
        $title = $request->title;

        $blogs = BlogDetail::where('title', 'LIKE', '%' . $title . '%')->where('publish',1)->get();

        if (empty($blogs)) {
            return response()->json([
                'status' => 400,
                'message' => "Error No data found",
                'data' => "",
            ], 400);
        } else {

            $data = BlogDetailResource::collection($blogs);
            return response()->json([

                'status' => 200,
                'message' => "Blog Detail",
                'data' => $data,
            ], 200);

        }

    }
}
