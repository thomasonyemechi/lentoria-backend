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
            return response(['error' => $validated->errors()], 422);

        }
        $category->name = $request->input('name');
        $category->save();
        return response(['success' => 'Category Added'], 200);
    }

    function edit($id)
    {
        $category = Category::find($id);

        return response($category, 200);
    }
}