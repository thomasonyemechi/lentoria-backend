<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //


    public function test()
    {
        return 'rjehnjnjnvjnv';
    }

    public function log2()
    {
        return auth()->user();
    }


    public function createUser()
    {
        User::create([
            'name' => 'Tester 1',
            'email' => 'tester@gmail.com',
            'password' => Hash::make('1234'),
        ]);

        return response('done');
    }



    public function login(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if ($validatedData->fails())
        {
            return response(['errors'=>$validatedData->errors()->all()], 422);
        }
        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response(['message' => 'Invalid Credentials'], 401);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['message' => 'Login successfull' , 'access_token' => $accessToken], 200);
    }


}
