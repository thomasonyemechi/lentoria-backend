<?php

namespace App\Http\Controllers;

use App\Models\Questionaire;
use App\Models\QuestionaireAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionaireController extends Controller
{
    //

    function addQuestion(Request $request)
    {
        $val = Validator::make($request->all(), [
            'question' => 'required|string|min:3',
            'a' => 'required|string',
            'b' => 'required|string',
            'c' => 'required|string',
            'd' => 'required|string',
            'type' => 'required|string'
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        Questionaire::create([
            'question' => $request->question,
            'a' => $request->a,
            'b' => $request->b,
            'c' => $request->c,
            'd' => $request->d,
            'type' => $request->type
        ]);

        return response([
            'message' => 'Question has been added sucessfully'
        ], 200);
    }


    function updateQuestion(Request $request)
    {
        $val = Validator::make($request->all(), [
            'question' => 'required|string|min:3',
            'a' => 'required|string',
            'b' => 'required|string',
            'c' => 'required|string',
            'd' => 'required|string',
            'type' => 'required|string',
            'question_id' => 'required|exists:questionaires,id'
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        Questionaire::where('id', $request->question_id)->update([
            'question' => $request->question,
            'a' => $request->a,
            'b' => $request->b,
            'c' => $request->c,
            'd' => $request->d,
            'type' => $request->type
        ]);

        return response([
            'message' => 'Question has been updated scuessfully'
        ], 200);
    }


    function fetchQuestions(Request $request)
    {
        $questions = Questionaire::paginate(100);

        return response([
            'data' => $questions
        ], 200);
    }


    function updateQuestionStatus(Request $request)
    {
        $val = Validator::make($request->all(), [
            'questions_ids' => 'required',
            'status' => 'required'
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        foreach($request->question_ids as $ques)
        {
            Questionaire::where('id', $ques)->update([
                'status' => $request->status
            ]);
        }

        return response([
            'message' => 'Questions have been uidpated sucessfully'
        ], 200);
        
    }



    function saveUserAnswers(Request $request) 
    {
        $val = Validator::make($request->all(), [
            'questions' => 'required',
            'type' => 'required'
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        QuestionaireAnswer::create([
            'user_id' => auth()->user()->id,
            'total_questions' => count($request->questions),
            'data' => json_encode($request->questions),
            'question_type' => $request->question_type
        ]);
        return response([
            'message' => 'Answers have been saved sucessfully'
        ], 200);       

    }



    function fetchUsersAnswerAll()
    {
        $answers = QuestionaireAnswer::orderby('id', 'desc')->paginate(['id', 'name', 'email', 'total_questions', 'question_type'], 150);
        return response([
            'data' => $answers
        ], 200);
    }

    function fetchSingleAnswer($id)
    {
        $answer = QuestionaireAnswer::where('id', $id)->first();
        return response([
            'data' => $answer
        ], 200);
    }


    function fetchAffilateQuestions()
    {
        $questions = Questionaire::where(['status' => 1, 'type' => 1])->get();
        return response([
            'data' => $questions
        ], 200);
    }


    function fetchInstructorQuestions()
    {
        $questions = Questionaire::where(['status' => 1, 'type' => 2])->get();
        return response([
            'data' => $questions
        ], 200);
    }
}
