<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LectureController extends Controller
{


    function updateVideoLink(Request $request)
    {
        $val = Validator::make($request->all(), [
            'lecture_id' => 'required|exists:lectures,id',
            'uri' => 'required'
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        $lecture = Lecture::find($request->lecture_id);
        $lecture->update([
            'main_content' => $request->uri
        ]);


        return response([
            'message' => 'Lecture Video has been updated scuessfully'
        ], 200);
    }

    public function checkVideoLink(Request $request)
    {
        $val = Validator::make($request->all(), [
            'lecture_id' => 'required|exists:lectures,id',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()], 422);
        }

        $uri = Lecture::find($request->lecture_id)->value('main_content');
        return response(['data' => $uri], 200);
    }
    public function addLecture(Request $request)
    {
        $val = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'section_id' => 'required|exists:sections,id',
            'description' => 'string',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        $total_lectures = Lecture::where(['section_id' => $request->section_id])->count() + 1;
        $lecture = Lecture::create([
            'title' => $request->title,
            'section_id' => $request->section_id,
            'description' => $request->description,
            'order' => $total_lectures,
        ]);

        return response(['message' => 'Lecture has been created successfully', 'id' => $lecture->id], 200);
    }
    public function updateLecture(Request $request)
    {
        $val = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'section_id' => 'required|exists:sections,id',
            'id' => 'required|exists:lectures,id',
            'description' => 'string',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        Lecture::where('id', $request->id)->update([
            'title' => $request->title,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);

        return response(['message' => 'Lecture has been updated successfully'], 200);
    }

    public function fetchLectures($section_id)
    {
        return response(['data' => Lecture::where('section_id', $section_id)->orderby('order', 'asc')->get()], 200);
    }

    public function orderLecture(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id' => 'required|exists:lectures,id',
            'action' => 'required',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        $lecture = Lecture::find($request->id);
        $action = $request->action;
        $current_lecture_order = $lecture->order;

        $before_current = ($action == 'up') ? Lecture::where(['section_id' => $lecture->section_id, ['order', '<', $lecture->order]])->orderBy('order', 'desc')->first() :
            Lecture::where(['section_id' => $lecture->section_id, ['order', '>', $lecture->order]])->orderBy('order', 'asc')->first();

        if (!$before_current) {
            return response([
                'message' => 'This item cannot be ordered',
            ], 422);
        }

        $lecture->update([
            'order' => $before_current->order,
        ]);

        $before_current->update([
            'order' => $current_lecture_order,
        ]);

        return response([
            'message' => 'Lecture has be ordered sucessfully',
        ], 200);
    }

    public function vidTest(Request $request)
    {
        $file = $request->file('video');
        $fileName = $file->hashName();
        $destinationPath = public_path() . '/assets/uploads/';
        $file->move($destinationPath, $fileName);
        return response(['data' => $request->all()], 200);
    }
}
