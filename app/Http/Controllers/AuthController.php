<?php

namespace App\Http\Controllers;

use App\Mail\SendResetLink;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required',
        ]);
        if ($validatedData->fails()) {
            return response(['errors' => $validatedData->errors()->all()], 422);
        }


        $res = Http::asForm()->post(env('LINK'), [
            'userlogin' => '...',
            'email' => $request->email,
            'password' => $request->password,
        ]);
        

        if (!$res['success']) {
            return response([
                'message' => $res['message'],
            ], 401);
        }

        $this->checkAndValidateUser($res['data']);

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response(['message' => 'Invalid Credentials'], 401);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        $user = auth()->user();

        $in = ($user->instructor) ? 1 : 0;
        $af = ($user->affiliate) ? 1 : 0;

        return response([
            'message' => 'Login successfull', 'access_token' => $accessToken, 'data' => $user, 'instructor' => $in, 'affiliate' => $af, 'admin' => $user->role
        ], 200);
    }

    public function checkAndValidateUser($data)
    {
        $data = json_decode(json_encode($data));
        User::updateOrCreate(
            ['live_id' => $data->sn],
            [
                'email' => $data->email,
                'lastname' => $data->lastname,
                'firstname' => $data->firstname,
                'phone' => $data->phone,
                'password' => $data->pass,
            ]
        );
    }

    public function signup(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'firstname' => 'string|required',
            'lastname' => 'string|required',
            'email' => 'email|required',
            'phone' => 'required',
            'password' => 'required',
        ]);
        if ($validatedData->fails()) {
            return response(['errors' => $validatedData->errors()->all()], 422);
        }

        $res = Http::asForm()->post(env('LINK'), [
            'userSignup' => '...',
            'email' => $request->email,
            'password' => $request->password,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
        ]);

        if (!$res['success']) {
            return response([
                'message' => $res['message'],
            ], 401);
        }
        $this->checkAndValidateUser($res['data']);

        return response([
            'message' => 'Signup sucessfull',
        ], 200);
    }


    function forgotPassword(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
        if ($validatedData->fails()) {
            return response(['errors' => $validatedData->errors()->all()], 422);
        }
        $token = rand(1000, 9999);
        PasswordReset::updateOrCreate(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => date('Y-M-j H:i:s')
            ]
        );

        Mail::to($request->email)->send(new SendResetLink($token));
        return response([
            'message' => 'Reset token has been sent sucessfully'
        ]) ;
    }



    function resetPassword(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'token' => 'required|min:4',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validatedData->fails()) {
            return response(['errors' => $validatedData->errors()->all()], 422);
        }

        $reset = PasswordReset::find($request->email);

        if(!$reset)
        {
            return response([
                'message' => 'No password reset request was made for this email'
            ]);
        }

        if(!password_verify($request->token, $reset->token)){
            return response([
                'message' => 'Invalid reset token'
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $hashed = Hash::make($request->password);
        $user->forceFill([
            'password' => $hashed
        ]);

        $user->save();
        $res = Http::asForm()->post(env('LINK'), [
            'live_id' => $user->live_id,
            'hashed' => $hashed,
            'updatePasswordLen' => 'update_me'
        ]);

        return response([
            'message' => 'Password reset was sucessful'
        ]);

    }
}
