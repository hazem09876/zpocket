<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AchievementApiController;
use App\Http\Controllers\Api\adminapiController;
use App\Http\Controllers\Api\AnswerApiController;
use App\Http\Controllers\Api\FeedbackApiController;
use App\Http\Controllers\Api\ModuleApiController;
use App\Http\Controllers\Api\QuestionApiController;
use App\Http\Controllers\Api\ScoreApiController;
use App\Http\Controllers\Api\UserModuleApiController;
use App\Http\Controllers\Api\VideoApiController;
use App\Http\Controllers\UserModuleController;


// User Routes
Route::get('/users/{id}', [UserApiController::class, 'getUserById']);
Route::post('/users/login', [UserApiController::class, 'userLogin']);
Route::post('/user', [UserApiController::class, 'createUser']);

Route::get('/user/getModules', [UserModuleApiController::class, 'getModules']);
Route::post('/user/registerModule/{user_id}', [UserModuleApiController::class, 'registerModule']);
Route::post('/user/takeModule/{user_id}', [UserModuleApiController::class, 'takeModule']);

// user get video for module
Route::get('/user/showVideo/{module_id}', [VideoApiController::class, 'getVideosByModule']);

// get questions for module
Route::get('/user/ModuleQuestions/{module_id}', [QuestionApiController::class, 'getModuleQuestions']);

Route::post('/user/UserQuestions', [AnswerApiController::class, 'getUserAnswers']);

//feedback 
Route::get('/feedbacks', [FeedbackApiController::class, 'getFeedbacks']);
Route::post('/feedbacks', [FeedbackApiController::class, 'store']);
Route::get('/feedbacks/user/{user_id}/module/{module_id}', [FeedbackApiController::class, 'getUserModuleFeedback']);

// answer for user 
Route::get('/user/ModuleAnswers/{module_id}', [AnswerApiController::class, 'getModuleAnswers']);


// Achievement Routes+
Route::get('/achievements', [AchievementApiController::class, 'getAchievements']);
Route::get('/achievements/{id}', [AchievementApiController::class, 'getAchievementById']);

// Admin Routes+
Route::get('/admin/users', [adminapiController::class, 'getUsers']);
Route::get('/admins', [adminapiController::class, 'getAdmins']);
Route::post('/admin/createModule', [adminapiController::class, 'createModule']);
Route::delete('/admin/removeModule/{id}', [AdminApiController::class, 'removeModule']);
Route::get('/admin/getModules', [adminapiController::class, 'getModules']);
Route::get('/admins/{id}', [adminapiController::class, 'getAdminById']);


Route::post('/admins/createVideo/{module_id}', [VideoApiController::class, 'createForModule']);
Route::delete('/admins/removeVideo/{video_id}', [VideoApiController::class, 'removeVideo']);
Route::post('/admins/AllVideos', [VideoApiController::class, 'AllVideos']);
Route::get('/admins/showVideo/{module_id}', [VideoApiController::class, 'getVideosByModule']);

// question Api for admin
Route::post('/admins/questions/{module_id}', [QuestionApiController::class, 'createQuestions']);
Route::delete('/admins/removequestions/{question_id}', [QuestionApiController::class, 'removeQuestion']);

// answers for admin
Route::post('/admins/answers/{question_id}', [AnswerApiController::class, 'createAnswers']);
Route::delete('/admins/removeanswers/{answer_id}', [AnswerApiController::class, 'removeAnswer']);
Route::get('/admins/ModuleAnswers/{module_id}', [AnswerApiController::class, 'getModuleAnswers']);

// Answer Routes
Route::post('/answers', [AnswerApiController::class, 'getAnswers']);
Route::get('/answers/{id}', [AnswerApiController::class, 'getAnswerById']);


// answer management 
Route::post('/questions/{question_id}/answers', [AnswerApiController::class, 'createAnswers']);
Route::delete('/answers/{answer_id}', [AnswerApiController::class, 'removeAnswer']);
Route::get('/modules/{module_id}/answers', [AnswerApiController::class, 'getModuleAnswers']);

//score
Route::post('/score', [ScoreApiController::class, 'storeScoreWithProgress']);
Route::get('/scores/user/{user_id}/module/{module_id}', [ScoreApiController::class, 'getUserModuleScores']);
Route::get('/scores/user/{user_id}/module/{module_id}/question/{question_id}', [ScoreApiController::class, 'getUserQuestionScore']);



// Get progress (optionally filtered by module)
Route::get('/users/{user_id}/progress', [ScoreApiController::class, 'getUserProgress']);
Route::get('/users/{user_id}/progress/{module_id}', [ScoreApiController::class, 'getUserProgress']);


// User Module Routes
Route::get('/user_modules', [UserModuleApiController::class, 'getUserModules']);
Route::get('/user_modules/{id}', [UserModuleApiController::class, 'getUserModuleById']);

// Video Routes

