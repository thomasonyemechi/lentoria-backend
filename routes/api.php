<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
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

Route::post('/user_login', [AuthController::class , 'login']);
Route::post('/user_signup', [AuthController::class , 'signup']);




Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:api']], function () {

    Route::get('/checklogged', [AuthController::class , 'log2']);

    Route::post('/add_category', [CategoryController::class , 'create']);
    Route::get('category/{id}', [CategoryController::class , 'edit']);


    Route::post('/category/{id}', [CategoryController::class , 'update']);

Route::post('/status', [CategoryController::class , 'status']);

});