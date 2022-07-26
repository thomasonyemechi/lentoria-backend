<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class InstructorController extends Controller
{
    function becomeInstructor(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        if ($validated->fails()) { return response(['errors' => $validated->errors()->all()], 422); }
        $user = User::find($request->id);
        $user->update([
            'role' => 5,
        ]);

        Instructor::updateOrCreate(['user_id' => $user->id],
        [ 'biography' => ' e' ]);

        return response([
            'message' => 'You have become an instructor, follow guidlines to create course'
        ], 200);
    }


    function updateInstructorProfile(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        if ($validated->fails()) { return response(['errors' => $validated->errors()->all()], 422); }

        $user = User::find($request->id);
        $user->instructor->update([
            'headline' => $request->headline,
            'biography' => $request->biography,
            'language' => $request->language,
            'website_url' => $request->website_url,
            'twitter' => $request->twitter,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'youtube' => $request->youtube
        ]);

        return response([
            'message' => 'Instructor info has been updated sucessfuly'
        ], 200);
    }


    function fetchAllInstructor()
    {
        $instructors = Instructor::with(['user'])->paginate(100);
        return response([
            'data' => $instructors
        ], 200);
    }


    function fetchSingleInstructor()
    {
        $user = User::with(['instructor'])->where('id', auth()->user()->id)->first();
        return response([
            'data' => $user
        ], 200);
    }
}
