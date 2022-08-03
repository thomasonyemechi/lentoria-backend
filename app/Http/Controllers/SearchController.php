<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function searchCourses(Request $request){
      $course = Course::where('title','LIKE',"%".$request->search."%")->orWhere('description','LIKE',"%".$request->search."%")->get();

      return response(['data' => $course],200);
    }
}
