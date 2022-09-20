<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function notify($user_id, $title, $message)
    {
        Notification::make([
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message
        ]);
        return;
    }
}
