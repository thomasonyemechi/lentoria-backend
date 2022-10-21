<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
        $total_section = Section::where(['course_id' => $request->course_id])->count() + 1;
        $section = Section::create([
            'course_id'=>$request->course_id,
            'title'=>$request->title,
            'course_gain'=>$request->gain,
            'order' => $total_section
        ]);
        return response(['message' => 'Section created successfully','id'=>$section->id],200);
    }

    public function getSections($course_id){
        return response(['data' => Section::where('course_id', $course_id)->with('lectures')->orderby('order', 'ASC')->get()], 200);
    }

    public function getSection($id){
        $section = Section::find($id);

        return response(['data'=>$section],200);
    }

    public function getSectionsBySlug($slug)
    {
        $cid = Course::query()->where('slug', $slug)->value('id');
        $section = Section::query()->where('course_id', $cid)->with('lectures:id,description,image,main_content,duration,section_id,title')->orderBy('order', 'ASC')->get();
        return response(['data' => $section], 200);
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


    function orderSection(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id' => 'required|exists:sections,id',
            'action' => 'required'
        ]);
        if ($val->fails()) { return response(['errors' => $val->errors()->all()], 422); }
        $section = Section::find($request->id); $action = $request->action; $current_section_order = $section->order;

        $before_current = ($action == 'up') ? Section::where(['course_id' => $section->course_id, ['order', '<', $section->order] ])->orderBy('order', 'desc')->first():
            Section::where(['course_id' => $section->course_id, ['order', '>', $section->order] ])->orderBy('order', 'asc')->first();

        if(!$before_current){
            return response([
                'message' => 'This item cannot be ordered'
            ], 422);
        }

        $section->update([
            'order' => $before_current->order
        ]);

        $before_current->update([
            'order' => $current_section_order
        ]);

        return response([
            'message' => 'Section has be ordered sucessfully'
        ], 200);
    }
}
