<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseInfo;
use App\Models\CourseOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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


    function courseInfoUpdate(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'what_you_will_learn' => 'required|array',
            'course_requirement' => 'required|exists:courses,id',
            'course_audience' => 'required|exists:courses,id',

        ]);

    }
}
