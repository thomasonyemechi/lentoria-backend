<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseInfo;
use App\Models\CourseOwner;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CourseController extends Controller
{

    function generateLink($length)
    {
        return substr(str_shuffle(str_repeat('123456789abcdefghijklmnopqrstuvwxyz_-ABCDEFGHIJKLMNOPQRSTUVWXYZ', $length)), 0, $length);
    }

    function validateLink(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'link' => 'required',
        ]);
        if ($validated->fails()) {
            return response(['error' => $validated->errors()->all()], 422);
        }
        $ck = Course::where(['link' => $request->link])->count();
        $message = ($ck > 0) ? 'This link has been taken, try another' : 'Link is available';
        $status = ($ck > 0) ? false : true;

        return response([
            'message' => $message,
            'status' => $status
        ], 200);
    }


    function getCourseFromLink($link)
    {
        $course = Course::where(['link' => $link])->first(['id', 'slug', 'title', 'link']);
        if($course) {
            return response([
                'data' => $course
            ], 200);
        }

        return response(['message' => 'No course with this link was found',
            'data' => ''
        ], 404);
    }


    function updateCourseLink(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'link' => 'required|unique:courses,link',
            'course_id' => 'required|exists:courses,id'
        ]);
        if ($validated->fails()) {
            return response(['error' => $validated->errors()->all()], 422);
        }

        Course::where(['id' => $request->course_id])->update([
            'link' => $request->link
        ]);

        return response([
            'message' => 'Short link has been updated sucessfully'
        ]);
    }


    public function courseUpdate(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:100',
            'description' => 'required',
            'language' => 'required',
            'level' => 'required',
            'category_id' => 'required|exists:categories,id',
            'course_type' => 'required',
            'topic_id' => 'required|exists:topics,id',
            'image' => 'image|mimes:jpeg,jpg,png,gif|dimensions:max_width=750,max_height=422',
            'video' => 'mimes:avi,mpeg,mp4',
        ]);
        if ($validated->fails()) {
            return response(['error' => $validated->errors()->all()], 422);
        }
        $old = Course::find($request->id);
        if ($request->file('image')) {

            $file = $request->file('image');
            $imageName = $file->hashName();
            $destinationPath = public_path() . '/assets/uploads/';
            if (($old->image != '' || $old->image != null) && file_exists($destinationPath . $old->image)) {
                unlink($destinationPath . $old->image);
            }
            $file->move($destinationPath, $imageName);
        }
        if ($request->file('video')) {
            $file2 = $request->file('video');
            $videoName = $file2->hashName();
            $destinationPath = public_path() . '/assets/uploads/';
            if (($old->video != '' || $old->video != null) && file_exists($destinationPath . $old->video)) {
                unlink($destinationPath . $old->video);
            }
            $file2->move($destinationPath, $videoName);
        }

        Course::where('id', $request->id)->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'language' => $request->language,
            'level' => $request->level,
            'category_id' => $request->category_id,
            'topic_id' => $request->topic_id,
            'course_type' => $request->course_type,
            'image' => $imageName ?? $old->image,
            'video' => $videoName ?? $old->video,
        ]);

        return response(['message' => 'Update Successful'], 200);
    }

    public function createCourse(Request $request)
    {
        $val = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:100',
            'course_type' => 'required',
            'category_id' => 'required|exists:categories,id',
            'topic_id' => 'required|exists:topics,id',
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        $slug = rand(111111, 9999999) .'_'. Str::slug($request->title);
        $course = Course::create([
            'user_id' => auth()->user()->id,
            'slug' => $slug,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'course_type' => $request->course_type,
            'category_id' => $request->category_id,
            'topic_id' => $request->topic_id,
            'link' => $this->generateLink(10),
        ]);

        CourseOwner::create([
            'user_id' => auth()->user()->id,
            'course_id' => $course->id,
            'status' => 1,
            'role' => 10,
        ]);

        CourseInfo::create([
            'course_id' => $course->id,
        ]);

        return response(['message' => 'Course has been created successfully', 'slug' => $slug], 200);
    }

    public function addCourseMessage(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);
        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }

        Course::where('id', $request->course_id)->update([
            'welcome_message' => $request->welcome_message,
            'certification_message' => $request->certification_message,
        ]);

        return response(['message' => 'Course Message Added'], 200);
    }

    public function fetchCourse($slug)
    {
        return response(['data' => Course::where('slug', $slug)->firstorfail()]);
    }

    public function fetchCourseLearners($slug)
    {
        return response(['data' => Course::with('category')->where('slug', $slug)->get()]);
    }

    public function fetchMyCourse()
    {
        // $data = User::with(['courses'])->find(auth()->user()->id);
        $data = Course::ofGetUser(auth()->user()->id)->paginate(25);

        return response(['data' => $data], 200);
    }

    public function updatePricing(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'price' => 'required',
        ]);
        if ($validated->fails()) {
            return response(['error' => $validated->errors()->all()], 422);
        }
        $course = Course::find($request->course_id);
        $course->update([
            'currency' => $request->currency,
            'price' => $request->price,
        ]);

        return response([
            'message' => 'Pricing info has been updaetd sucessfully',
        ], 200);
    }

    public function courseInfoUpdate(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'what_you_will_learn' => 'required|',
            'course_requirement' => 'required|',
            'course_audience' => 'required|',
            'opportunities' => 'string'
        ]);


        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }


        $course = Course::find($request->course_id);

        CourseInfo::updateOrCreate(['course_id' => $course->id], [
            'what_you_will_learn' => json_encode($request->what_you_will_learn),
            'course_requirement' => json_encode($request->course_requirement),
            'course_audience' => json_encode($request->course_audience),
            'purpose' => json_encode($request->purpose),
            'opportunities' => $request->opportunities
        ]);

        return response([
            'message' => 'Course info has been updated sucessfully',
        ], 200);
    }

    function coursesByCategory($id)
    {
        $courses = Course::with('user')->where('category_id', $id)->paginate(25);

        return response(['data' => $courses], 200);
    }

    function getCoursesRandomly()
    {
        $courses = Course::with('user')->inRandomOrder()->limit(30)->get();

        return response(['data' => $courses], 200);
    }

    function fetchCourseByTypeGroupByCategory($type_id)
    {
        //$categories = Category::inRandomOrder()->with('published_courses')->get();
        $categories = Category::inRandomOrder()->limit(10)->get();
        foreach ($categories as $index => $cat) {
            $course = Course::with(['user:id,firstname,lastname'])->where(['category_id' => $cat->id, 'published' => 1, 'course_type' => $type_id])->inRandomOrder()->limit(10)->get();
            $categories[$index]['courses'] = $course;
        }

        $new_arr = [];
        foreach ($categories as $cat) {
            $ct = count($cat['courses']);
            if ($ct > 0) {
                $new_arr[] = $cat;
            }
        }
        return $new_arr;
    }

    function fetchCourseByTypeGroupByCategoryAll()
    {
        $types = Type::inRandomOrder()->limit(3)->get();
        foreach ($types as $index => $type) {
            $types[$index]['categories'] = $this->fetchCourseByTypeGroupByCategory($type->id);
        }
        return response([
            'data' => $types
        ], 200);
    }
}
