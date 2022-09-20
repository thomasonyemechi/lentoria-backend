<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ForumMessage;
use App\Models\Lecture;
use App\Models\Transaction;
use App\Models\VirtualClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VirtualClassController extends Controller
{
    function addContentToclass(Request $request)
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
            'data' => $content
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

    function fetchClassStudents($course_id)
    {
        $students = Transaction::with(['user:id,firstname,lastname'])->where(['course_id' => $course_id])->paginate(500);
        return response([
            'data' => $students
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
        if($sn == 'video'){   $val = $lecture->main_content;   }
        elseif($sn == 'image') { $val = $lecture->image; }
        elseif($sn == 'text') { $val = $lecture->text; }
        elseif($sn == 'code') { $val = $lecture->code; }
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


    /////forum controller

    function sendMessage(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'message' => 'string|required',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        $message = ForumMessage::create([
            'message' => trim($request->message),
            'sender_id' => auth()->user()->id,
            'reply' => $request->reply,
            'course_id' => $request->course_id
        ]);
        if($request->reply) {
            $title = auth()->user()->firstname.' '.auth()->user()->lastname.' replied to your message';
            $course = Course::find($request->course_id, ['id', 'title']);
            $message = 'Your message was replied to in a '.$course->title.' Forum';
            $this->notify($request->reply, $title, $message);
        }
        return response([
            'data' => $message,
            'message' => 'Message posted sucessfuly'
        ], 200);
    }


    function fetchAllMessages($course_id)
    {
        $allmessages = ForumMessage::with(['user:id,firstname,lastname', 'reply'])->where(['course_id' => $course_id])->paginate(250);
        return response([
            'data' => $allmessages
        ], 200);
    }


    function mySentMessage()
    {
        $mymessages = ForumMessage::with(['course:id,title'])->where(['sender_id' => auth()->user()->id ])->paginate(150);
        return response([
            'data' => $mymessages
        ], 200);
    }
}
