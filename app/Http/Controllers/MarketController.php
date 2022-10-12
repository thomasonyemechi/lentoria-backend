<?php

namespace App\Http\Controllers;

use App\Models\UserMarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarketController extends Controller
{
    function addItemToMarket(Request $request)
    {
        $val = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }

        $ck = UserMarket::where(['user_id' => auth()->user()->id, 'course_id' => $request->course_id ])->count();
        if($ck > 0) {
            return response([
                'message' => 'Item is already on your list'
            ], 406);
        }

        UserMarket::create([
            'course_id' => $request->course_id,
            'user_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'Course hass been added to your list'
        ], 200);
    }


    function removeItemFromList(Request $request)
    {
        $val = Validator::make($request->all(), [
            'item_id' => 'required|exists:courses,id',
        ]);
        if ($val->fails()) {
            return response(['errors' => $val->errors()->all()], 422);
        }
    }
}
