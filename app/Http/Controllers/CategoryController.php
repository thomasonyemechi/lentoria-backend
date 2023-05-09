<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
        ]);

        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
        return response(['message' => 'Category Added'], 200);
    }

    function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => "required|unique:categories,name,$request->category_id",
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }

        Category::where('id', $request->category_id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response(['message' => 'Category has been updated successfully',]);
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
        foreach ($data as $key => $cat) {
            $data[$key]['total_topics'] = Topic::where('category_id', $cat->id)->count();
        }
        return response(['data' => $data]);
    }

    function findCategoryBySlug($slug)
    {
        $category = Category::where('slug', $slug)->first();
        return response([
            'data' => $category
        ], 200);
    }

    function activeCategories()
    {
        $data = Category::where('status', 1)->whereHas('topics')->with('topics')->orderBy('id', 'desc')->get();
        return response(['data' => $data], 200);
    }

    function fetchSingleCategory($id)
    {
        $category =  Category::with(['topics'])->find($id);
        return response(['data' => $category], 200);
    }
    function categories()
    {
        $categories = Category::whereHas('courses')->get();
        return response(['data' => $categories], 200);
    }
}
