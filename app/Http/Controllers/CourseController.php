<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseInfo;
use App\Models\CourseOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function courseUpdate(Request $request){

        $validated = Validator::make($request->all(),[
            'title'=>'required|string|max:100',
            'subtitle'=>'required|string|max:100',
            'description'=>'required',
            'language'=>'required',
            'level'=>'required',
            'category_id'=>'required|exists:categories,id',
            'type'=>'required',
            'topic_id'=>'required|exists:topics,id',
            'image'=>'mimes:jpeg,jpg,png,gif',
            'video'=>'mimes:avi,mpeg,mp4',
        ]);
        if($validated->fails()){return response(['error' => $validated->errors()->all()], 422);}


        if($request->file()){
            $old = Course::find($request->id);
            $file = $request->file('image') ;
            $imageName = $file->hashName() ;
            $destinationPath = public_path().'/assets/uploads/' ;
            if(file_exists($destinationPath.$old->image)){
                unlink($destinationPath.$old->image);
            }
            $file->move($destinationPath,$imageName);

            $file2 = $request->file('video');
            $videoName = $file2->hashName();
            $destinationPath = public_path().'/assets/uploads/';
            if(file_exists($destinationPath.$old->video)){
                unlink($destinationPath.$old->video);
            }
            $file2->move($destinationPath,$videoName);
        }



        Course::where('id',$request->id)->update([
            'title'=>$request->title,
            'subtitle'=>$request->subtitle,
            'description'=>$request->description,
            'language'=>$request->language,
            'level'=>$request->level,
            'category_id'=>$request->category_id,
            'topic_id'=>$request->topic_id,
            'course_type'=>$request->type,
            'image'=>$imageName,
            'video'=>$videoName,
        ]);

        return response(['message'=>'Update Successful'],200);
    }
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
