<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    function createCourseType(Request $request)
    {
        $val = Validator::make($request->all(), [
            'type' => 'required|string|unique:types,type',
            'description' => 'required|string'
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        Type::create([
            'type' => $request->type,
            'description' => $request->description
        ]);

        return response([
            'message' => 'Type has been added sucessfuly'
        ], 200);
    }

    function fetchTypes()
    {
        $types = Type::all();
        return response([
            'message' => $types
        ], 200);
    }


    function fetchTypesAdmin()
    {
        $types = Type::withCount('courses')->get();
        return response([
            'data' => $types
        ], 200);
    }

    function updateType(Request $request)
    {
        $val = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'type' => 'required|string|unique:types,type',
            'description' => 'required|string'
        ]);

        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        Type::where(['id' => $request->type_id])->update([
            'type' => $request->type,
            'description' => $request->description
        ]);

        return response([
            'message' => 'Type has been updated sucessfuly'
        ], 200);
    }
}
