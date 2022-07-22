<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/testing', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'login']);





Route::group(['middleware' => ['auth:api'] ], function (){

    Route::get('/checklogged', [AuthController::class, 'log2']);

});
