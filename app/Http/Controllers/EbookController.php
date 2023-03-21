<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EbookController extends Controller
{
    function catchEbook(Request $request)
    {
        
    }


    function getEbooksDownloads(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);
        // if(isset->)
        $res = Http::get('');
    }
}
