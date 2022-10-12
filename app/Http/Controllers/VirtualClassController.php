<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\VirtualClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VirtualClassController extends Controller
{
    function addContentToClass(Request $request)
    {
        $val = Validator::make($request->all(), [
            'lecture_id' => 'required|exists:lectures,id',
            'comment' => 'string',
            'content' => 'string'
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        $lecture = Lecture::find($request->lecture_id);
        $comment = ($request->content == 'comment') ? $request->comment : $this->contentRet($request->content, $lecture);
        $content = VirtualClassroom::create([
            'course_id' => $lecture->section->course_id,
            'section_id' => $lecture->section_id,
            'lecture_id' => $lecture->id,
            'user_id' => auth()->user()->id,
            'content' => $request->content,
            'comment' => $comment,
        ]);

        return response([
            'message' => 'Content has been posted to classroom',
        ], 200);
    }


    function fetchClassContent($course_id)
    {
        $contents = VirtualClassroom::where(['course_id' => $course_id])->paginate(100);
        return response([
            'data' => $contents
        ], 200);
    }

    function fetchClassContentBySection($section_id)
    {
        $contents = VirtualClassroom::where(['section_id' => $section_id])->paginate(100);
        return response([
            'data' => $contents
        ], 200);
    }


    function fetchClassContentByLecture($lecture_id)
    {
        $contents = VirtualClassroom::where(['lecture_id' => $lecture_id])->paginate(100);
        return response([
            'data' => $contents
        ], 200);
    }

    function contentRet($sn, $lecture)
    {
        $val = $lecture->main_content;
        if ($sn == 'video') {
            $val = $lecture->main_content;
        } elseif ($sn == 'image') {
            $val = $lecture->image;
        } elseif ($sn == 'text') {
            $val = $lecture->text;
        } elseif ($sn == 'code') {
            $val = $lecture->code;
        }
        return $val;
    }

    // function content($sn)
    // {
    //     $val = 1;
    //     if($sn == 'video'){   $val = 1;   }
    //     elseif($sn == 'image') { $val = 2; }
    //     elseif($sn == 'text') { $val = 3; }
    //     elseif($sn == 'code') { $val = 4; }
    //     return $val;
    // }

    public function getComments($lecture_id)
    {
        $validate = Validator::make(['lecture_id'=>$lecture_id],[
            'lecture_id'=>'required|exists:lectures,id',
        ]);
        if($validate->fails()){return response(['errors'=>$validate->errors()->all()],422);}

        return response(["data"=>VirtualClassroom::where(['lecture_id' => $lecture_id,'content' => 'comment'])->orderBy('id','ASC')->take(10)->get(['comment','created_at'])]);
    }


    function addSchedule(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'ymd' => 'required|string',
            'starts' => 'required|string',
            'ends' => 'required|string'
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        $ymd = date('ymd',strtotime($request->ymd));

        $ck = Schedule::where(['ymd' => $ymd, 'course_id' => $request->course_id])->count();
        if($ck > 0) { 
            return response([
                'message' => 'A schedule already exists for this day'
            ], 403);
        }

        Schedule::create([
            'course_id' => $request->course_id,
            'ymd' => $ymd,
            'starts' => $request->starts,
            'ends' => $request->ends
        ]);

        return response([
            'message' => 'Schedule has been created sucessfully'
        ]);
    }


    function editSchedule(Request $request)
    {
        $val = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'ymd' => 'required|string',
            'starts' => 'required|string',
            'ends' => 'required|string'
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        $schedule = Schedule::find($request->schedule_id);
        Schedule::where('id' , $request->schedule_id)->update([
            'starts' => $request->starts,
            'ends' => $request->ends
        ]);


        return response([
            'schedule has been updated sucessfully'
        ], 200);
    }

    function deleteSchedule(Request $request)
    {
        $val = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        Schedule::where('id', $request->schedule_id)->delete();
        
        return response([
            'message' => 'Schedule has been deleted sucessfully'
        ],);
    }
}
