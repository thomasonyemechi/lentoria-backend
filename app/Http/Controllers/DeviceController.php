<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    function logDevice($data)
    {
        $system_id = $data->system_id;

        $dev = Device::where('system_id',  $system_id)->first();
        if($dev) {}
        else {
            Device::create([
                'user_id' => auth()->user()->id,
                'user_agent' => $data->user_agent,
                'browser_name' => $data->name,
                'verison' => $data->version,
                'platform' => $data->platform,
                'pattern' => $data->pattern,
                'system_id' => $system_id,
                'logged_in' => $data->logged_id
            ]);

            ///send a message to the user about ne device logged in 
            ///device can be flagged and blocked/loggedout
        }
        return;
    }
}
