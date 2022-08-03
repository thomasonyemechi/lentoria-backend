<?php

use App\Http\Controllers\AnnoucementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SectionController;
use Illuminate\Http\Request;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WishlistController;
use App\Models\Section;
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
    Route::get('/topics/{id}', [TopicController::class, 'getTopicsByCategory']);



    //Course routes

    Route::post('/create_new_course', [CourseController::class, 'createCourse']);
    Route::post('/course_update', [CourseController::class, 'courseUpdate']);
    Route::post('/course_update_info', [CourseController::class, 'courseInfoUpdate']);
    Route::get('/fetch_my_course', [CourseController::class, 'fetchMyCourse']);
    Route::get('/course/{slug}', [CourseController::class, 'fetchCourse']);




    //Section routes

    Route::post('add_section',[SectionController::class, 'createSection']);
    Route::post('update_section', [SectionController::class, 'updateSection']);
    Route::get('get_sections/{course_id}',[SectionController::class, 'getSections']);
    Route::get('get_single_section/{id}',[SectionController::class, 'getSection']);
    Route::get('sections_lectures',[SectionController::class, 'getSectionWithLectures']);
    Route::post('order_section',[SectionController::class, 'orderSection']);



    ///lectures

    Route::post('add_lecture',[LectureController::class, 'addLecture']);
    Route::get('fetch_lectures/{section_id}',[LectureController::class, 'fetchLectures']);
    Route::post('order_lecture',[LectureController::class, 'orderLecture']);

    //materials

    Route::post('add_materials',[MaterialController::class, 'createMaterial']);
    Route::get('get_materials/{lecture_id}',[MaterialController::class, 'getMaterials']);
    Route::get('get_material/{id}',[MaterialController::class, 'getMaterial']);
    Route::post('update_material', [MaterialController::class, 'updateMaterial']);

    //annoucements
    Route::post('add_announcement', [AnnoucementController::class, 'createAnnouncement']);
    Route::get('get_announcements/{id}', [AnnoucementController::class, 'getAnnouncements']);

    //Wishlist
    Route::post('add_to_wishlist',[WishlistController::class,'addToWishlist']);
    Route::get('my_wishlist', [WishlistController::class, 'userWishList']);
    Route::post('delete_from_wishlist', [WishlistController::class, 'deleteFromWishlist']);

    //Search
    Route::get('search_courses',[SearchController::class, 'searchCourses']);

    //BuyCourse

    Route::post('buy_course',[TransactionController::class,'buyCourse']);
});