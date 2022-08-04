<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    function create(Request $request)
    {
        $category = new Category();

        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
        ]);

        if ($validated->fails()) { return response(['errors' => $validated->errors()->all()], 422); }
        $category->name = $request->input('name');
        $category->save();
        return response(['message' => 'Category Added'], 200);
    }

    function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
        ]);

        if ($validated->fails()) { return response(['errors' => $validated->errors()->all()], 422); }

        Category::where('id', $request->id)->update([
            'name' => $request->name
        ]);

        return response(['message' => 'Category has been updated sucesfully',]);

    }

    function status(Request $request)
    {
        $category = Category::find($request->id);
        $new_status = ($category->status == 1) ? 0 : 1;
        $category->update([
            'status' => $new_status
        ]);
        return response(['message' => 'success'], 200);
    }


    function fetchCategory()
    {
        $data = Category::orderby('id', 'desc')->get();
        foreach($data as $key => $cat){
            $data[$key]['total_topics'] = Topic::where('category_id', $cat->id)->count();
        }
        return response(['data' => $data]);
    }

    function fetchSingleCategory($id){
      $category=  Category::with(['topics'])->find($id);
        return response(['data' => $category],200);
    }
}
