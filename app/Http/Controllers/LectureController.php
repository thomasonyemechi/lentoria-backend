<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LectureController extends Controller
{
    function addLecture(Request $request)
    {
        $val = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'section_id' => 'required|exists:sections,id',
            'description' => 'string'
        ]);
        if ($val->fails()) { return response(['errors' => $val->errors()->all()], 422); }
        $total_lectures = Lecture::where(['section_id' => $request->section_id])->count() + 1;
        Lecture::create([
            'title' => $request->title,
            'section_id' => $request->section_id,
            'description' => $request->description,
            'order' => $total_lectures
        ]);

        return response([
            'message' => 'Lecture has been created scueffuly'
        ], 200);
    }


    function fetchLectures($section_id)
    {
        return response(['data' => Lecture::where('section_id', $section_id)->orderby('order', 'asc')->get()], 200);
    }

    function orderLecture(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id' => 'required|exists:lectures,id',
            'action' => 'required'
        ]);
        if ($val->fails()) { return response(['errors' => $val->errors()->all()], 422); }
        $lecture = Lecture::find($request->id); $action = $request->action; $current_lecture_order = $lecture->order;

        $before_current = ($action == 'up') ? Lecture::where(['section_id' => $lecture->section_id, ['order', '<', $lecture->order] ])->orderBy('order', 'desc')->first():
            Lecture::where(['section_id' => $lecture->section_id, ['order', '>', $lecture->order] ])->orderBy('order', 'asc')->first();

        if(!$before_current){
            return response([
                'message' => 'This item cannot be ordered'
            ], 422);
        }

        $lecture->update([
            'order' => $before_current->order
        ]);

        $before_current->update([
            'order' => $current_lecture_order
        ]);

        return response([
            'message' => 'Lecture has be ordered sucessfully'
        ], 200);
    }

}
