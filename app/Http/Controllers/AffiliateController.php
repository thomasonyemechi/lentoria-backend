<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AffiliateController extends Controller
{

    function logMyLink()
    {

    }

    
    function getCompensationPlan()
    {
        $res = Http::get(env('LINK'), [
            'compensationPlans' => 'compensationPlans'
        ]);
        $res = json_decode($res,1);
        return response($res);
    }

    function inactiveReferral($live_id)
    {
        $res = Http::get(env('LINK'), [
            'inactiveReferrals' => $live_id
        ]);
        $res = json_decode($res,1);
        return response($res);
    }




    function activeReferral($live_id)
    {
        $res = Http::get(env('LINK'), [
            'activeReferrals' => $live_id
        ]);
        $res = json_decode($res,1);
        return response($res);
    }

    function accountSummary($live_id)
    {
        $res = Http::get(env('LINK'), [
            'accountSummary' => $live_id
        ]);
        $res = json_decode($res,1);
        return response($res);
    }


    function getUsersRecentTransactions($live_id)
    {
        $res = Http::get(env('LINK'), [
            'recentTransactions' => $live_id
        ]);
        $res = json_decode($res,1);
        return response($res);
    }

    
    function getUsersAllTransactions($live_id)
    {
        $res = Http::get(env('LINK'), [
            'page' => $_GET['page'] ?? 1,
            'allTransactions' => $live_id
        ]);
        $res = json_decode($res,1);
        foreach($res['links'] as $ln => $val){
            $res['links'][$ln]  = '/api/affiliate/all_transaction/'.$live_id.'?page='.$val;
        }
        return response($res);
    }

}
