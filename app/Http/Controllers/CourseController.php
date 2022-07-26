<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseInfo;
use App\Models\CourseOwner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    function createCourse(Request $request)
    {
        $val = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:100',
            'course_type' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($val->fails()) { return response(['errors' => $val->errors()->all()], 422); }

        $course = Course::create([
            'user_id' => auth()->user()->id,
            'slug' => rand(111111,9999999).Str::slug($request->title),
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'course_type' => $request->course_type,
            'category_id' => $request->category_id
        ]);


        CourseOwner::create([
            'user_id' => auth()->user()->id,
            'course_id' => $course->id,
            'status' => 1,
            'role' => 10
        ]);


        CourseInfo::create([
            'course_id' => $course->id
        ]);

        return response([
            'message' => 'Course has been created scuessfully'
        ], 200);
    }


    function fetchMyCourse()
    {
        $data = User::with(['courses'])->find(auth()->user()->id);
        return response([
            'data' => $data
        ], 200);
    }



    function courseInfoUpdate(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'what_you_will_learn' => 'required|array',
            'course_requirement' => 'required|array',
            'course_audience' => 'required|array',
        ]);

        if ($val->fails()) { return response(['errors' => $val->errors()->all()], 422); }

        $course = Course::find($request->id);

        $course->info->update([
            'what_you_will_learn' => json_encode($request->what_you_will_learn),
            'course_requirement' => json_encode($request->course_requirement),
            'course_audience' => json_encode($request->course_audience),
            'purpose' => json_encode($request->purpose),
        ]);

        return response([
            'message' => 'Course info has been updated sucessfully'
        ], 200);
    }
}
