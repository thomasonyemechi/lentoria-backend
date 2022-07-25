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

<<<<<<< HEAD
Route::get('/testing', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/add_category', [CategoryController::class , 'create']);
Route::get('/category/{$id}', [CategoryController::class , 'edit']);
=======
Route::post('/user_login', [AuthController::class, 'login']);
Route::post('/user_signup', [AuthController::class, 'signup']);
>>>>>>> 1d051626b911bb5c2924afcb198e40ce64c74620







Route::group(['prefix'=>'control', 'as'=>'control.', 'middleware' => ['auth:api'] ], function (){

    Route::get('/checklogged', [AuthController::class, 'log2']);

});