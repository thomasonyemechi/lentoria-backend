<?php

namespace App\Http\Controllers;

use App\Models\Annoucement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnoucementController extends Controller
{
    public function createAnnouncement(Request $request){
        $validated = Validator::make($request->all(),[
            'course_id' => 'required|exists:courses,id',
            'title'=>'required|string|max:100',
            'content'=>'required',  
        ]);
        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }
        Annoucement::create([
            'course_id' => $request->course_id,
            'title' =>$request->title,
            'content' =>$request->content,
        ]);
        return response(['message' => 'Annoucement Added'], 200);
    }

    public function getAnnouncements($course_id){
        return response(['data'=>Annoucement::where('course_id',$course_id)->get()], 200);
    }
}