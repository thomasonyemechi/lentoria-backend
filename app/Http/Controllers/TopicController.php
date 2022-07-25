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
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validation->fails()) {
            return response(['errors' => $validation->errors()->all()], 422);
        }

        $topics = Topic::create($request->all());

        return response(['message' => 'Topic added successfully'], 200);
    }

    public function getTopics()
    {
        $topics = Topic::get()->load(['category']);

        return response(['data' => $topics], 200);
    }

    function update(Request $request)
    {
        Topic::where('id', $request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response(['message' => 'Category has been updated sucesfully',],200);

    }

}
