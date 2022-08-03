<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseOwner;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function buyCourse(Request $request){
        $validated = Validator::make($request->all(),[
            'transaction_id'=>'unique:transactions,transaction_id',
            'course_id'=>'exists:courses,id',
        ]);
        if($validated->fails()){return response(['errors'=>$validated->errors()->all()],422);}

        $course = Course::find($request->course_id);


        Transaction::create([
            'transaction_id'=> $request->transaction_id,
            'user_id'=>auth()->user()->id,
            'course_owner_id'=> $course->user_id,
            'course_id'=>$request->course_id,
            'amount'=>$request->amount,
            'status'=> 3,
        ]);
        return response(['message'=>'course_purchased'], 200);
    }
}