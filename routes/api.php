<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use Illuminate\Http\Request;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\CourseController;
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

Route::post('/user_login', [AuthController::class, 'login']);
Route::post('/user_signup', [AuthController::class, 'signup']);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:api']], function () {
    // category routes
    Route::post('/add_category', [CategoryController::class, 'create']);
    Route::get('category/{id}', [CategoryController::class, 'edit']);
    Route::post('/category/{id}', [CategoryController::class, 'update']);
    Route::get('/category', [CategoryController::class, 'fetchCategory']);
    Route::post('/status', [CategoryController::class, 'status']);


    // Instructor fetch
    Route::post('/become_instructor', [InstructorController::class, 'becomeInstructor']);
    Route::post('/update_instructor_profile', [InstructorController::class, 'updateInstructorProfile']);
    Route::get('/fetch_all_instructor', [InstructorController::class, 'fetchAllInstructor']);
    Route::get('/fetch_single_instructor', [InstructorController::class, 'fetchSingleInstructor']);



    Route::get('/fetchcategory/{id}', [CategoryController::class, 'fetchSingleCategory']);
    // Topic routes
    Route::post('/add_topic', [TopicController::class, 'createTopic']);
    Route::post('/update_topic/{id}', [TopicController::class, 'updateTopic']);
    Route::get('/topic/{id}', [TopicController::class, 'getTopic']);
    Route::get('/topics', [TopicController::class, 'getTopics']);



    //Course routes

    Route::post('/create_new_course', [CourseController::class, 'createCourse']);
    Route::post('/course_update', [CourseController::class, 'courseUpdate']);

});
