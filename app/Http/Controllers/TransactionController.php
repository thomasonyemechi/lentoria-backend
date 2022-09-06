<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseOwner;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{


    function purchaseFromWallet(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'exists:courses,id',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        $buyer = auth()->user();


        $request->transaction_id = mt_rand(100000000000, 999999999999);

        $course = Course::find($request->course_id);
        $purchased = Transaction::where(['user_id' => $buyer->id, 'course_id' => $course->id])->count();
        if ($purchased > 0) {
            return response(['message' => 'You have already purchased this course'], 406);
        }
        ////check if you are the course owner and stops you from purchasing your own course
        if ($buyer->id == $course->user_id) {
            return response(['message' => 'You cannot purchase your own course'], 422);
        }

        ///start course purchase ...

        $transaction = Transaction::create([
            'transaction_id' => $request->transaction_id,
            'course_id' => $course->id,
            'course_owner_id' => $course->user->id,
            'user_id' => $buyer->id,
            'amount' => $course->price,
            'status' => 0
        ]);

        $res = Http::asForm()->post(env('LINK'), [
            'purchaseCourse' => 256,
            'transaction_id' => $request->transaction_id,
            'course_id' => $course->id,
            'course_owner' => $course->user->live_id,
            'course_buyer' => $buyer->live_id,
            'amount' => $course->price,
        ]);

        $res = json_decode($res);

        if ($res->success) {
            $transaction->update([
                'status' => 1
            ]);

            return response([
                'message' => 'Course has been purchased/register for sucessfully'
            ], 200);
        }

        return response([
            'message' => 'Error processing transaction, Pls retry'
        ], 400);
    }


    function buyCourseWithCard(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'transaction_id' => 'required'
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        $buyer = auth()->user();
        $course = Course::find($request->course_id);
        $purchased = Transaction::where(['user_id' => $buyer->id, 'course_id' => $course->id])->count();
        if ($purchased > 0) {
            return response(['message' => 'You have already purchased this course'], 406);
        }
        ////check if you are the course owner and stops you from purchasing your own course
        if ($buyer->id == $course->user_id) {
            return response(['message' => 'You cannot purchase your own course'], 422);
        }

        ///start course purchase ...

        $transaction = Transaction::create([
            'transaction_id' => $request->transaction_id,
            'course_id' => $course->id,
            'course_owner_id' => $course->user->id,
            'user_id' => $buyer->id,
            'amount' => $course->price,
            'status' => 0
        ]);

        $res = Http::asForm()->post(env('LINK'), [
            'purchaseCourse' => 256,
            'transaction_id' => $request->transaction_id,
            'course_id' => $course->id,
            'course_owner' => $course->user->live_id,
            'course_buyer' => $buyer->live_id,
            'amount' => $course->price,
        ]);

        $res = json_decode($res);

        if ($res->success) {
            $transaction->update([
                'status' => 1
            ]);

            return response([
                'message' => 'Course has been purchased/register for sucessfully'
            ], 200);
        }

        return response([
            'message' => 'Error processing transaction, Pls retry'
        ], 400);
    }


    // public function buyCourse(Request $request)
    // {
    //     $validated = Validator::make($request->all(), [
    //         'transaction_id' => 'unique:transactions,transaction_id',
    //         'course_id' => 'exists:courses,id',
    //     ]);
    //     if ($validated->fails()) {
    //         return response(['errors' => $validated->errors()->all()], 422);
    //     }

    //     $course = Course::find($request->course_id);


    //     Transaction::create(['transaction_id' => $request->transaction_id,
    //         'user_id' => auth()->user()->id,
    //         'course_owner_id' => $course->user_id,
    //         'course_id' => $request->course_id,
    //         'amount' => $request->amount,
    //         'status' => 3,
    //     ]);
    //     return response(['message' => 'course_purchased'], 200);
    // }


    function retryPurchase(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'exists:courses,id',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
        $user = auth()->user();

        $tran = Transaction::where([
            'course_id' => $request->course_id,
            'user_id' => $user->id
        ]);

        if($tran->status == 1) {

        }
    }



    function fetchLiveBalance($live_id)
    {
        $res = Http::asForm()->post(env('LINK') . '?balance=' . $live_id,);
        return json_decode($res);
    }
}