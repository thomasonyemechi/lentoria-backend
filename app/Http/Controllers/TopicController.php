<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    public function createTopic(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|unique:topics,name',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validation->fails()) {
            return response(['errors' => $validation->errors()->all()], 422);
        }

        $topics = Topic::create($request->all());

        return response(['message' => 'Topic added successfully'], 200);
    }

    public function getTopic($id){
        $topic = Topic::find($id);

        return response(['data'=>$topic],200);
    }

    public function getTopics()
    {
        $topics = Topic::with(['category:id,name'])->orderBy('id', 'desc')->get();
        foreach($topics as $key =>$topic) {
            $topics[$key]['total_courses'] = Course::where('topic_id', $topic->id)->count();
        }

        return response(['data' => $topics], 200);
    }

    public function getTopicsByCategory($cat_id){
        $topics = Topic::where('category_id',$cat_id)->get();
        return response(['data' => $topics], 200);

    }

    public function updateTopic(Request $request,$id)
    {
        Topic::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id'=> $request->category_id,
        ]);
        return response(['message' => 'Topic has been updated sucesfully',],200);

    }



}