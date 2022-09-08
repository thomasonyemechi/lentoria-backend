<?php

namespace App\Http\Controllers;

use App\Models\ActivationPayment;
use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PackageController extends InstructorController
{

    function fetcLivepetalPackagesAll()
    {
        $res = Http::asForm()->post(env('LINK').'?all_plan=456789',);
        return response([
            'data' => json_decode($res)
        ], 200);
    }

    function fetcLivepetalPackagesSingle($plan_id)
    {
        $res = Http::asForm()->post(env('LINK').'?plan='.$plan_id,);
        return response([
            'data' => json_decode($res)
        ], 200);
    }

    function activateFromCard(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'instructor' => 'required',
            'plan' => 'required',
            'transaction_id' => 'required'
        ]);
        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }
        $user = auth()->user();
        $res = $this->activatePlan($user->live_id, $request->plan);
        $this->catchActivation($user->id, $request->transaction_id, $res->amount, 'Activation from card', $res->errors);
        if ($res->active) {
            $this->registerAffliate($user->id);
            if ($request->instructor == 1) {
                $this->becomeInstructor($user->id);
                return response([
                    'message' => 'You are have sucessfully upgraded you package'
                ], 200);
            }
            return response(['message' => 'You are have sucessfully upgraded you package'
            ], 200);
        }
        return response([
            'message' => $res->message
        ], 402);
    }



    function activateFromWallet(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'instructor' => 'required',
            'plan' => 'required',
        ]);

        if ($validated->fails()) {
            return response(['errors' => $validated->errors()->all()], 422);
        }
        $user = auth()->user();
        $trno = rand(11111111111, 999999999999999);
        $res = $this->activatePlan($user->live_id, $request->plan);
        $this->catchActivation($user->id, $trno, $res->amount, 'Activation from wallet', $res->errors);
        if ($res->active) {
            $this->registerAffliate($user->id);
            if ($request->instructor == 1) {
                $this->becomeInstructor($user->id);
                return response([
                    'message' => 'You are have sucessfully upgraded you package'
                ], 200);
            }
            return response(['message' => 'You are have sucessfully upgraded you package'
            ], 200);
        }
        return response([
            'message' => $res->message,
        ], 402);
    }



    function activatePlan($live_id, $plan)
    {
        $res = Http::asForm()->post(env('LINK'), [
            'live_id' => $live_id,
            'package_id' => $plan,
            'activatePackage' => 6454,
        ]);
        return json_decode($res);
    }


    function catchActivation($id, $trno, $amt, $rem, $others)
    {
        ActivationPayment::create([
            'user_id' => $id,
            'transaction_id' => $trno,
            'amount' => $amt,
            'remark' => $rem,
            'others' => json_encode($others)
        ]);
        return;
    }

    function registerAffliate($id)
    {
        Affiliate::updateOrCreate([
            'user_id' => $id
        ]);
        return;
    }

    function fetchLivepetalPlan($live_id)
    {
        $res = Http::asForm()->post(env('LINK'), [
            'live_id' => $live_id,
            'userPackage' => 34567
        ]);
        return json_decode($res);
    }
}