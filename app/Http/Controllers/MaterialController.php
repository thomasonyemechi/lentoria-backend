<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function createMaterial(Request $request)
    {
        $validated = Validator::make($request->all(), [
        'lecture_id' => 'required|exists:lectures,id',
        'title' => 'required|string|max:100',
        'type' => 'required',
        'link' => 'required_if:type,==,2|url',
        'file' => 'required_if:type,==,1|mimes:zip,pdf,mp4,mpeg',
    ]);

        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }
        if ($request->file()) {
            $file = $request->file('file');
            $fileName = $file->hashName();
            $destinationPath = public_path().'/assets/uploads/';
            $file->move($destinationPath, $fileName);
        }
        Material::create([
        'lecture_id' => $request->lecture_id,
        'title' => $request->title,
        'type' => $request->type,
        'link' => $request->link,
        'file_name' => $fileName,
    ]);

        return response(['message' => 'Material Added'], 200);
    }

    public function getMaterials($lecture_id)
    {
        $validated = Validator::make(['id' => $lecture_id], [
            'id' => 'exists:lectures,id',
        ]);
        if ($validated->fails()) {
            return response(['error' => $validated->errors()->all()], 422);
        }

        return response(['data' => Material::where('lecture_id', $lecture_id)->get()], 200);
    }

    public function getMaterial($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'exists:materials,id',
        ]);
        if ($validated->fails()) {
            return response(['error' => $validated->errors()->all()], 422);
        }

        return response(['data' => Material::where('id', $id)->get()], 200);
    }

    public function updateMaterial(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'lecture_id' => 'required|exists:lectures,id',
            'title' => 'required|string|max:100',
            'type' => 'required',
            'link' => 'required_if:type,==,2|url',
            'file' => 'required_if:type,==,1|mimes:zip,pdf,mp4,mpeg',
        ]);

        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }
        $old = Material::find($request->id);
        if ($request->file()) {
            $file = $request->file('file');
            $fileName = $file->hashName();
            $destinationPath = public_path().'/assets/uploads/';
            // if (file_exists($destinationPath.$old->file_name)) {
            //     unlink($destinationPath.$old->file_name);
            // }
            $file->move($destinationPath, $fileName);
        }
        Material::where('id',$request->id)->update([
            'lecture_id' => $request->lecture_id,
            'title' => $request->title,
            'type' => $request->type,
            'link' => $request->link,
            'file_name' => $fileName,
        ]);

        return response(['message' => 'Material Updated'], 200);
    }
}