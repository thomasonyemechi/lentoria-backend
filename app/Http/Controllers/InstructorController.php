<?php

namespace App\Http\Controllers;

use App\Models\ActivationPayment;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Questionaire;
use App\Models\QuestionaireAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;


class InstructorController extends Controller
{

    function becomeInstructorFromLink()
    {
        $user = auth()->user();
        $this->becomeInstructor($user->id);

        return response([
            'message' => 'You are now an instructor'
        ], 200);
    }

    public function becomeInstructor($id)
    {
        $user = User::find($id);
        $user->update([
            'role' => 5,
        ]);
        Instructor::updateOrCreate(['user_id' => $user->id]);
    }

    public function updateInstructorProfile(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }

        $user = User::find($request->id);
        $user->instructor->update([
            'headline' => $request->headline,
            'biography' => $request->biography,
            'language' => $request->language,
            'website_url' => $request->website_url,
            'twitter' => $request->twitter,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'youtube' => $request->youtube,
        ]);

        return response([
            'message' => 'Instructor info has been updated sucessfuly',
        ], 200);
    }

    public function fetchAllInstructor()
    {
        $instructors = Instructor::with(['user'])->paginate(100);
        return response([
            'data' => $instructors,
        ], 200);
    }

    public function fetchSingleInstructor()
    {
        $user = User::with(['instructor'])->where('id', auth()->user()->id)->first();
        return response([
            'data' => $user,
        ], 200);
    }

    public function fetchInstructorByCourseId($id)
    {
        $course = Course::findOrFail($id);
        return response([
            'data' => [
                'course_info' => collect($course)->forget('user')->all(),
                'basic_info' => collect($course->user)->forget('instructor')->all(),
                'instructor' => $course->user->instructor,
                'other_info' => $course->info,
            ],
        ]);
    }


    public function fetchInstructorById($id)
    {
        $user = User::findOrFail($id);
        $courses_no = Course::ofGetUser($id)->count();
        return response([
            'data' => [
                'basic_info' => collect($user)->forget('instructor')->all(),
                'instructor_info' => $user->instructor,
                'no_of_courses' => $courses_no,
            ],
        ]);
    }

    public function fetchCoursesForInstructor($id)
    {
        $courses = Course::ofGetUser($id)->latest()->paginate(25);
        return response([
            'data' => $courses,
        ]);
    }

    public function getInstructorProfile()
    {
        $instructor = Instructor::where('user_id', auth()->id())->get();
        return response(['data' => $instructor], 200);
    }


    function uploadProfilePicture(Request $request)
    {
        $val = Validator::make($request->all(), [
            'image' => 'required|file|max:2048'
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        $user = Auth::user();
        $old_image = $user->instructor->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = 'profile_pic_'.$file->hashName();
            $destinationPath = public_path() . '/assets/uploads/';
            if (($old_image != '' || $old_image != null) && file_exists($destinationPath . $old_image)) {
                unlink($destinationPath . $old_image);
            }
            $file->move($destinationPath, $imageName);

            Instructor::where('user_id', $user->id)->update([
                'image'  => $imageName
            ]);
        }
        

        return response([
            'message' => 'Profile Picture has been updated successfully'
        ]);

    }

    public function checkIfQnaireAnsd()
    {
        $answer_exists = QuestionaireAnswer::where('user_id', '=', auth()->id())->exists();

        if ($answer_exists) {
            return response(['data' => true], 200);
        }

        return response(['data' => false,], 200);
    }
}
