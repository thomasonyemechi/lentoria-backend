<?php

namespace App\Http\Controllers;

use App\Models\ActivationPayment;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;


class InstructorController extends Controller
{
    public function becomeInstructor($id)
    {
        $user = User::find($id);
        $user->update([
            'role' => 5,
        ]);
        Instructor::updateOrCreate(['user_id' => $user->id]);
    }



    function becomeInstructor01()
    {
        $user = auth()->user();
        $live_user = $this->fetchLivepetalPlan($user->live_id);
        if($live_user->active) {
            $this->becomeInstructor($user->id);
            return response([
                'message' => 'You are now an instructor'
            ], 200);
        }
        return response([
            'message' => 'Please activate a livepetal membership package to become an instructor'
        ], 402);
    }


    function becomeInstructor02(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'amount' => 'required|integer',
            'transaction_id' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }

        $user = auth()->user();
        ActivationPayment::create([
            'user_id' => $user->id,
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'remark' => 'Account Activation Fee'
        ]);

        $res = Http::asForm()->post(env('LINK'), [
            'live_id' => $user->live_id,
            'activateBasicPackage' => 6454,
        ]);

        $res = json_decode($res);

        if($res->active) {
            $this->becomeInstructor($user->id);
            return response([
                'message' => 'You are now an instructor'
            ], 200);
        }

        return response([
            'message' => $res->message
        ], 400);
    }


    function fetchLivepetalPlan($live_id){
        $res = Http::asForm()->post(env('LINK'), [
            'live_id' => $live_id,
            'userPackage' => 34567
        ]);

        return json_decode($res);
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
}