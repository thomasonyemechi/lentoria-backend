<?php

use App\Http\Controllers\AnnoucementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PublishController;
use App\Http\Controllers\QuestionaireController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SectionController;
use Illuminate\Http\Request;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TypeController;
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
Route::get('/fetchcourse_by_type', [CourseController::class, 'fetchCourseByTypeGroupByCategoryAll']);
Route::get('fetch_faq/{course_id}', [FaqController::class, 'fetchFaq']);
Route::post('/vid', [LectureController::class, 'vidTest']);
Route::get('/course_info2/{slug}', [CourseController::class, 'fetchCourse']);
Route::post('/has_course', [TransactionController::class, 'hasCourse']);
Route::get('/fetch_live_plans', [PackageController::class, 'fetcLivepetalPackagesAll']);
Route::get('/fetch_live_plan/{plan_id}', [PackageController::class, 'fetcLivepetalPackagesSingle']);
Route::get('/get_course_from_link/{link}', [CourseController::class, 'getCourseFromLink']);

Route::get('/get_topic_by_slug/{slug}', [TopicController::class, 'findTopicbySlug']);
Route::get('/get_category_by_slug/{slug}', [CategoryController::class, 'findCategoryBySlug']);


Route::get('/fetch_affilate_questions', [QuestionaireController::class, 'fetchAffilateQuestions']);
Route::get('/fetch_instructor_questions', [QuestionaireController::class, 'fetchInstructorQuestions']);



Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:api']], function () {
    

    ///become instructor
    Route::post('/activate_from_wallet', [PackageController::class, 'activateFromWallet']);
    Route::post('/activate_from_card', [PackageController::class, 'activateFromCard']);
    Route::get('/fetch_all_instructor', [InstructorController::class, 'fetchAllInstructor']);
    Route::get('/fetch_single_instructor', [InstructorController::class, 'fetchSingleInstructor']);
    Route::get('/instructor_info', [InstructorController::class, 'getInstructorProfile']);


    //Wishlist
    Route::post('add_to_wishlist', [WishlistController::class, 'addToWishlist']);
    Route::get('my_wishlist', [WishlistController::class, 'userWishList']);
    Route::post('delete_from_wishlist', [WishlistController::class, 'deleteFromWishlist']);

    //Search
    Route::get('search_courses', [SearchController::class, 'searchCourses']);

    //BuyCourse
    Route::post('buy_course', [TransactionController::class, 'buyCourse']);
    Route::post('wallet_purchase', [TransactionController::class, 'purchaseFromWallet']);
    Route::post('card_purchase', [TransactionController::class, 'buyCourseWithCard']);



    ///other routes
    Route::get('balance/{live_id}', [TransactionController::class, 'fetchLiveBalance']);

    Route::get('/category', [CategoryController::class, 'fetchCategory']);
    Route::get('/topic/{id}', [TopicController::class, 'getTopic']);
    Route::get('/topics', [TopicController::class, 'getTopics']);
    Route::get('/topics/{id}', [TopicController::class, 'getTopicsByCategory']);
    Route::get('/fetchcategory/{id}', [CategoryController::class, 'fetchSingleCategory']);
    Route::get('/get_type_admin', [TypeController::class, 'fetchTypesAdmin']);


    Route::group(['middleware' => ['admin']], function () {
        //questionaires....
        Route::post('/add_questionaire', [QuestionaireController::class, 'addQuestion']);
        Route::post('/update_questionaire', [QuestionaireController::class, 'updateQuestion']);
        Route::get('/fetch_questionaire', [QuestionaireController::class, 'fetchQuestions']);

        
        // category routes
        Route::post('/add_category', [CategoryController::class, 'create']);
        Route::post('/category', [CategoryController::class, 'update']);
        Route::post('/status', [CategoryController::class, 'status']);

        // Topic routes
        Route::post('/add_topic', [TopicController::class, 'createTopic']);
        Route::post('/update_topic/{id}', [TopicController::class, 'updateTopic']);

        // Course Types
        Route::post('/add_type', [TypeController::class, 'createCourseType']);
        Route::post('/update_type', [TypeController::class, 'updateType']);

        Route::get('/under_review_courses', [PublishController::class, 'fetchCoursesUnderReview']);


    });


    Route::group(['middleware' => ['instructor']], function () {
        // Instructor
        Route::post('/update_instructor_profile', [InstructorController::class, 'updateInstructorProfile']);

        //short Links
        Route::post('/vaildate_link', [CourseController::class, 'validateLink']);
        Route::get('/generate_link/{length}', [CourseController::class, 'generateLink']);
        Route::post('/update_link', [CourseController::class, 'updateCourseLink']);

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

        ////faq
        Route::post('add_faq', [FaqController::class, 'addFaq']);
        Route::post('edit_faq', [FaqController::class, 'editFaq']);
        Route::post('delete_faq', [FaqController::class, 'deleteFaq']);
        Route::get('fetch_faq/{course_id}', [FaqController::class, 'fetchFaq']);
        Route::get('plan/{live_id}', [InstructorController::class, 'fetchLivepetalPlan']);

        //Section routes
        Route::post('add_section', [SectionController::class, 'createSection']);
        Route::post('update_section', [SectionController::class, 'updateSection']);
        Route::get('get_sections/{course_id}', [SectionController::class, 'getSections']);
        Route::get('get_single_section/{id}', [SectionController::class, 'getSection']);
        Route::get('sections_lectures', [SectionController::class, 'getSectionWithLectures']);
        Route::post('order_section', [SectionController::class, 'orderSection']);
        Route::get('sections_lectures/{slug}', [SectionController::class, 'getSectionsBySlug']);

        ///lectures
        Route::post('add_lecture', [LectureController::class, 'addLecture']);
        Route::get('fetch_lectures/{section_id}', [LectureController::class, 'fetchLectures']);
        Route::post('order_lecture', [LectureController::class, 'orderLecture']);
        Route::post('update_lecture_video', [LectureController::class, 'updateVideoLink']);
<<<<<<< HEAD
        Route::post('update_lecture_code', [LectureController::class, 'updateLectureCodes']);
        Route::post('update_lecture_article', [LectureController::class, 'updateLectureArticle']);
        Route::post('update_lecture_image', [LectureController::class, 'updateLectureImage']);
=======
        Route::post('update_lecture_text', [LectureController::class, 'updateTextcontent']);
>>>>>>> cba2ced22f8dd420a189285bff9d5ff51669f98e
        Route::post('update_lecture', [LectureController::class, 'updateLecture']);
        Route::post('get_video_link', [LectureController::class, 'checkVideoLink']);

        //materials
        Route::post('add_materials', [MaterialController::class, 'createMaterial']);
        Route::get('get_materials/{lecture_id}', [MaterialController::class, 'getMaterials']);
        Route::get('get_material/{id}', [MaterialController::class, 'getMaterial']);
        Route::post('update_material', [MaterialController::class, 'updateMaterial']);

        //annoucements
        Route::post('add_announcement', [AnnoucementController::class, 'createAnnouncement']);
        Route::get('get_announcements/{id}', [AnnoucementController::class, 'getAnnouncements']);


        ///
        Route::post('publish_course', [PublishController::class, 'publishCourse']);

    });
});