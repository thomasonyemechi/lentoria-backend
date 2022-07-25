<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    function create(Request $request)
    {
        $category = new Category();

        $validated = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validated->fails())
        {
            return response(['errors' => $validated->errors()->all()], 422);

        }
        $category->name = $request->input('name');
        $category->save();
        return response(['success' => 'Category Added'], 200);
    }

    function update(Request $request)
    {

        $category = Category::find($request->id);

        $category->name = $request->name;
        $category->save();

        return response($category, 200);

    }

    function status(Request $request)
    {

        $category = Category::find($request->id);

        if ($category->status == 1)
        {
            $category->status = 0;
        }
        else
        {
            $category->status = 1;
        }

        $category->save();

        return response([$category, 'message' => 'success'], 200);
    }
}