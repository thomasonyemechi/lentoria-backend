<?php

namespace App\Http\Controllers;

use App\Models\ActivationPayment;
use App\Models\Affiliate;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function fetchAllCourses()
    {
        $courses = Course::orderby('id', 'desc')->paginate(150);

        return response([
            'data' => $courses
        ]);

    }


    function fetchPublishedCourses()
    {
        $courses = Course::where(['published' => 1])->orderby('updated_at')->paginate(150);
        return response([
            'data' => $courses
        ], 200);
    }

    function fetchRejectedCourses()
    {
        $courses = Course::where(['published' => 5])->orderby('updated_at')->paginate(150);

        return response([
            'data' => $courses
        ], 200);
    }


    function fetchCoursePurchases()
    {
        $purchases = Transaction::with(['owner:id,name', 'course:id,title', 'user:id,name'])
        ->where(['status' => 1])->orderby('updated_at')->paginate(250);

        return response([
            'data' => $purchases
        ], 200);
    }


    function fetchAccountUpgrades()
    {
        $upgrades = ActivationPayment::with(['user'])->orderby('id', 'desc')->paginate(150);
        return response([
            'data' => $upgrades
        ], 200);
    }


    function fetchAllAffiliate()
    {
        $affiliates = Affiliate::with(['user'])->orderby('updated_at')->paginate(150);
        return response([
            'data' => $affiliates
        ], 200);
    }


    function fetchAllInstructors() {
        $instructors = Instructor::with('user')->orderby('updated_at')->paginate(100);
        return response([
            'data' => $instructors
        ], 200);
    }


    function fetchAdmins()
    {
        $admins = User::where(['role' => 1])->get();
        return response([
            'data' => $admins
        ], 200);
    }

}
