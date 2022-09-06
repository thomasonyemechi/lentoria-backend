<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseInfo;
use App\Models\Instructor;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class PublishController extends Controller
{

    function fetchCoursesUnderReview()
    {
        $courses = Course::where(['published' => 5])->orderby('id', 'desc')->paginate(150);
        return response([
            'data' => $courses
        ], 200);
    }

    
    function publishCourse(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id'
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        $course = Course::find($request->course_id); $user = auth()->user();

        // if($course->published > 0) {
        //     return response([
        //         'message' => 'You can not resubmit this course for update'
        //     ], 400);
        // }

        if($course->user_id != $user->id){
            return response(['message' => 'You are trying to upload a course that does not belong to you'], 401);
        }

        $sections_err = $this->checkLectures($course->id)['errors'];
        $info_err = $this->checkCourseInfo($request->course_id)['errors'];
        $pro_err = $this->checkInstrcutorProfile($course->user_id)['errors'];
        $cou_err = $this->checkCourse($course->id)['errors'];


        $total_err = array_merge($info_err, $pro_err);
        $total_err = array_merge($cou_err, $total_err);
        $total_err = array_merge($sections_err, $total_err);

        if(count($total_err) == 0) {
            $course->update([
                'published' => 5
            ]);

            return response([
                'message' => 'Your course has been submitted for review'
            ], 200);
        }


        return response([
            'message' => 'You cannot pubish this course because some requirements have not been met',
            'errors' => $total_err
        ], 400);


    }


    function checkLectures($course_id)
    {
        $sections = Section::where('course_id', $course_id)->get(); $errs = [];
        if(count($sections) < 5) {
            $errs[] = 'You must have at least five section in your curriculum';
        }
        $empty_lecrure = 0;
        $empty_section = 0;

        foreach($sections as $sec) {
            $lectures = $sec->lectures;
            if(count($lectures) == 0) {
                $empty_section ++ ;
            }
            foreach($lectures as $lec)
            {
                if($lectures->main_content == ''){
                    $empty_lecrure ++;
                }
            }
        }

        if($empty_lecrure > 0) { $errs[] = $empty_lecrure.' lecture are without content'; }
        if($empty_section > 0) { $errs[] = $empty_section.' section are without lecture'; }

        return [
            'errors' => $errs,
            'count' => count($errs)
        ];
    }

    function checkCourse($course_id)
    {
        $course = Course::find($course_id);

        $errs = [];

        if($course->video == ''){
            $errs[] = 'No course  promotional video found';
        }

        if($course->image == '') {
            $errs[] = 'Course image not found';
        }

        if($course->desctiption == ''){
            $errs[] = 'Course description is too short';
        }

        if($course->level == 0) {
            $errs[] = 'Please specify the course level';
        }

        if($course->price == 0) {
            $errs[] = 'Price attached to course is too small';
        }

        return [
            'errors' => $errs,
            'count' => count($errs)
        ];
    }



    function checkInstrcutorProfile($user_id)
    {
        $instructor = Instructor::where(['user_id' => $user_id])->first();
        $errs = [];
        if($instructor->biography == ''){
            $errs[] = 'Biography has not been uploaded';
        }

        if($instructor->headline == ''){
            $errs[] = 'Profile Headline has not been uploaded';
        }
        return ['errors' => $errs, 'count' => count($errs)];
        
    }


    function checkCourseInfo($course_id)
    {
        $info = CourseInfo::where(['course_id' => $course_id])->first(); $errs = [];
        $what_you_learn =  ($info->what_you_will_learn) ? count(json_decode($info->what_you_will_learn)) : 0 ;
        $requirement =  ($info->course_requirement) ? count(json_decode($info->course_requirement)) : 0 ;
        $audience =  ($info->course_audience) ? count(json_decode($info->course_audience)) : 0 ;
        $purpose =  ($info->purpose) ? count(json_decode($info->purpose)) : 0 ;

        if($what_you_learn < 4) {
            $errs[] = 'What student will learn from taking your course is less than four';
        }

        if($requirement == 0) {
            $errs[] = 'Requirements for taking this course has not been uploaded';
        }

        if($audience == 0) {
            $errs[] = 'There are no specified audience for this course';
        }

        if($purpose == 0) {
            $errs[] = 'Purpose of taking the course is not stated';
        }

        if($info->opportunities == '') {
            $errs[] = 'Please specify job opportunities available from taking your course';
        }

        return ['errors' => $errs, 'count' => count($errs) ];
    }

    
}