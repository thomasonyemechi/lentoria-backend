<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{

    public function createTopic(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
        ]);
        if ($validation->fails()) {
            return response(['errors' => $validation->errors()->all()], 401);
        }
        $topics = Topic::create($request->all());
        return response(['message' => 'Topics Created Successfully'], 200);
    }
}