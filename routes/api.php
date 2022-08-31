<?php

use App\Http\Controllers\AnnoucementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SectionController;
use Illuminate\Http\Request;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WishlistController;
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
Route::get('/course_info/{id}', [InstructorController::class, 'fetchInstructorByCourseId']);
Route::get('/instructor_info/{id}', [InstructorController::class, 'fetchInstructorById']);
Route::get('/instructor_courses/{id}', [InstructorController::class, 'fetchCoursesForInstructor']);
Route::get('/categories', [CategoryController::class, 'categories']);
Route::get('/courses', [CourseController::class, 'getCoursesRandomly']);
Route::get('/courses/{id}', [CourseController::class, 'coursesByCategory']);
Route::get('get_sections/{course_id}', [SectionController::class, 'getSections']);
Route::get('fetch_lectures/{section_id}', [LectureController::class, 'fetchLectures']);
Route::get('/category', [CategoryController::class, 'activeCategories']);



Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:api']], function () {
    // category routes
    Route::post('/add_category', [CategoryController::class, 'create']);
    Route::get('category/{id}', [CategoryController::class, 'edit']);
    Route::post('/category/{id}', [CategoryController::class, 'update']);
    Route::get('/category', [CategoryController::class, 'fetchCategory']);
    Route::post('/status', [CategoryController::class, 'status']);


    // Instructor fetch
    Route::post('/become_instructor', [InstructorController::class, 'becomeInstructor01']);
    Route::post('/payto_become_instructor', [InstructorController::class, 'becomeInstructor02']);
    Route::post('/update_instructor_profile', [InstructorController::class, 'updateInstructorProfile']);
    Route::get('/fetch_all_instructor', [InstructorController::class, 'fetchAllInstructor']);
    Route::get('/fetch_single_instructor', [InstructorController::class, 'fetchSingleInstructor']);
    Route::get('/instructor_info', [InstructorController::class, 'getInstructorProfile']);
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
    Route::get('/course/landing_info/{slug}', [CourseController::class, 'fetchCourse']);
    Route::get('/course/intended_learners/{slug}', [CourseController::class, 'fetchCourseLearners']);
    Route::get('/course/{slug}', [CourseController::class, 'fetchCourse']);
    Route::post('/course_messageupdate', [CourseController::class, 'addCourseMessage']);
    Route::post('/update_price', [CourseController::class, 'updatePricing']);




    //Section routes

    Route::post('add_section', [SectionController::class, 'createSection']);
    Route::post('update_section', [SectionController::class, 'updateSection']);
    Route::get('get_sections/{course_id}', [SectionController::class, 'getSections']);
    Route::get('get_single_section/{id}', [SectionController::class, 'getSection']);
    Route::get('sections_lectures', [SectionController::class, 'getSectionWithLectures']);
    Route::post('order_section', [SectionController::class, 'orderSection']);



    ///lectures

    Route::post('add_lecture', [LectureController::class, 'addLecture']);
    Route::get('fetch_lectures/{section_id}', [LectureController::class, 'fetchLectures']);
    Route::post('order_lecture', [LectureController::class, 'orderLecture']);
    Route::post('update_lecture_video', [LectureController::class, 'updateVideoLink']);
    Route::post('update_lecture', [LectureController::class, 'updateLecture']);

    
    //materials

    Route::post('add_materials', [MaterialController::class, 'createMaterial']);
    Route::get('get_materials/{lecture_id}', [MaterialController::class, 'getMaterials']);
    Route::get('get_material/{id}', [MaterialController::class, 'getMaterial']);
    Route::post('update_material', [MaterialController::class, 'updateMaterial']);

    //annoucements
    Route::post('add_announcement', [AnnoucementController::class, 'createAnnouncement']);
    Route::get('get_announcements/{id}', [AnnoucementController::class, 'getAnnouncements']);

    //Wishlist
    Route::post('add_to_wishlist', [WishlistController::class, 'addToWishlist']);
    Route::get('my_wishlist', [WishlistController::class, 'userWishList']);
    Route::post('delete_from_wishlist', [WishlistController::class, 'deleteFromWishlist']);

    //Search
    Route::get('search_courses', [SearchController::class, 'searchCourses']);

    //BuyCourse

    Route::post('buy_course', [TransactionController::class, 'buyCourse']);


    ////faq
    Route::post('add_faq', [FaqController::class, 'addFaq']);
    Route::post(
        'edit_faq',
        [FaqController::class, 'editFaq']
    );
    Route::post('delete_faq', [FaqController::class, 'deleteFaq']);
    Route::get('fetch_faq/{course_id}', [FaqController::class, 'fetchFaq']);
    Route::get('plan/{live_id}', [InstructorController::class, 'fetchLivepetalPlan']);


    ///other routes 
    Route::get('balance/{live_id}', [TransactionController::class, 'fetchLiveBalance']);

});
