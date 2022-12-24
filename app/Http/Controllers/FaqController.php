<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    function addFaq(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'question' => 'required|string|min:3',
            'answer' => 'required|string|min:2'
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        $faq = Faq::create([
            'course_id' => $request->course_id,
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return response([
            'message' => 'Faq added sucesfully',
            'id' => $faq->id
        ], 200);
    }


    function editFaq(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id' => 'required|exists:faqs,id',
            'question' => 'required|string|min:3',
            'answer' => 'required|string|min:2'
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        $faq = Faq::find($request->id);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return response([
            'message' => 'Faq has been updated sucessfully'
        ], 200);
    }


    function deleteFaq(Request $request)
    {
        Faq::where('id', $request->id)->delete();
        return response([
            'message' => 'Faq deleted sucessfully'
        ], 200);
    }


    function fetchFaq($course_id)
    {
        $faqs = Faq::where(['course_id' => $course_id])->get();
        return response([
            'data' => $faqs
        ], 200);
    }

    public function fetchFaqBySlug($slug)
    {
        $course_id = Course::where('slug', $slug)->firstOrFail('id');
        $faqs = Faq::where(['course_id' => $course_id])->get(['id', 'question', 'answer']);
        return response(['data' => $faqs], 200);
    }
}
