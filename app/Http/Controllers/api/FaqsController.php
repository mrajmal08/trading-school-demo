<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alldata = Faqs::all();
        return response()->json([
            'status' => 200,
            'message' => "All Faq",
            'data' => $alldata,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'message' => 'Error in validation',
                'errors' => $validator->errors(),
            ], 400);
        }
        $data = Faqs::create($validator->validated());

        return response()->json([
            'status' => 201,
            'message' => 'Faq create successfully',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function show(Faqs $faqs)
    {

        return response()->json([
            'status' => 200,
            'message' => "All Faqs",
            'data' => $faqs,
        ], 200);

        // if (empty($faqs)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //         'data' => "",
        //     ], 400);
        // } else {
        //     return response()->json([
        //         'status' => 200,
        //         'message' => "All Rules",
        //         'data' => $faqs,
        //     ], 200);
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function edit(Faqs $faqs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faqs $faqs)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'message' => 'Error in validation',
                'errors' => $validator->errors(),
            ], 400);
        }
        $data = $faqs->updateOrCreate($validator->validated());
        return response()->json([
            'status' => 200,
            'message' => 'Faq update successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faqs $faqs)
    {
        $faqs->delete();
        return response()->json([
            'status' => 201,
            'message' => 'Faq delete successfully',
        ], 201);
    }
}
