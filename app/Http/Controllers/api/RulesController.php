<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'status' => 200,
            'message' => "All Rules",
            'data' => Rules::all('uuid', 'title', 'description'),
        ], 200);
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
        $id = Rules::create($validator->validated());
        return response()->json([
            'status' => 201,
            'message' => 'Rules create successfully', 'data' => $id,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function show(Rules $rules)
    {

        return response()->json([
            'status' => 200,
            'message' => "All Rules",
            'data' => $rules->only(['uuid', 'title', 'description']),
        ], 200);

        // if (empty($rules)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => "Error No data found",
        //         'data' => "",
        //     ], 400);
        // } else {
        //     return response()->json([
        //         'status' => 200,
        //         'message' => "All Rules",
        //         'data' => $rules->only(['uuid', 'title', 'description']),
        //     ], 200);
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function edit(Rules $rules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rules $rules)
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

        $data = $rules->updateOrCreate($validator->validated());

        return response()->json([
            'status' => 200,
            'message' => 'Rules update successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rules $rules)
    {
        $rules->delete();
        return response()->json([
            'status' => 201,
            'message' => 'Rules delete successfully',
        ], 201);
    }
}
