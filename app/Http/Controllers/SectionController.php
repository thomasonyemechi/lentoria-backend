<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function createSection(Request $request){
        $validation = Validator::make($request->all(),[
            'course_id'=>'required|exists:courses,id',
            'title'=>'required|string|max:100',
            'gain'=>'required|string',
        ]);
        if($validation->fails()){return response(['errors' => $validation->errors()->all()], 422);}

        Section::create([
            'course_id'=>$request->course_id,
            'title'=>$request->title,
            'course_gain'=>$request->gain,
        ]);
        return response(['message' => 'Section created successfully'],200);
    }

    public function getSections(){
        return response(['data'=>Section::all()],200);
    }

    public function getSection($id){
        $section = Section::find($id);

        return response(['data'=>$section],200);
    }

    public function getSectionWithLectures(){
        $sections = Section::with(['lectures:id,title'])->get();

        return response(['data'=>$sections],200);
    }

    public function updateSection(Request $request){
        $validation = Validator::make($request->all(),[
            'course_id'=>'required|exists:courses,id',
            'title'=>'required|string|max:100',
            'gain'=>'required|string',

        ]);
        if($validation->fails()){return response(['errors'=>$validation->errors()->all()],422);}
        Section::find($request->id)->update([
            'course_id'=>$request->course_id,
            'title' => $request->title,
            'course_gain' => $request->gain,
        ]);

        return response(['message' => 'Section has been updated successfully'],200);
    }
}
