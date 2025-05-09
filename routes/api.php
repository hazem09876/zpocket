<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AchievementApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\AnswerApiController;
use App\Http\Controllers\Api\FeedbackApiController;
use App\Http\Controllers\Api\LevelApiController;
use App\Http\Controllers\Api\ModuleApiController;
use App\Http\Controllers\Api\QuestionApiController;
use App\Http\Controllers\Api\QuizApiController;
use App\Http\Controllers\Api\ScoreApiController;
use App\Http\Controllers\Api\UserModuleApiController;
use App\Http\Controllers\Api\VideoApiController;

// User Routes
Route::get('/users', [UserApiController::class, 'getUsers']);
Route::get('/users/{id}', [UserApiController::class, 'getUserById']);

// Achievement Routes
Route::get('/achievements', [AchievementApiController::class, 'getAchievements']);
Route::get('/achievements/{id}', [AchievementApiController::class, 'getAchievementById']);

// Admin Routes
Route::get('/admins', [AdminApiController::class, 'getAdmins']);
Route::get('/admins/{id}', [AdminApiController::class, 'getAdminById']);

// Answer Routes
Route::get('/answers', [AnswerApiController::class, 'getAnswers']);
Route::get('/answers/{id}', [AnswerApiController::class, 'getAnswerById']);

// Feedback Routes
Route::get('/feedbacks', [FeedbackApiController::class, 'getFeedbacks']);
Route::get('/feedbacks/{id}', [FeedbackApiController::class, 'getFeedbackById']);

// Level Routes
Route::get('/levels', [LevelApiController::class, 'getLevels']);
Route::get('/levels/{id}', [LevelApiController::class, 'getLevelById']);

// Module Routes
Route::get('/modules', [ModuleApiController::class, 'getModules']);
Route::get('/modules/{id}', [ModuleApiController::class, 'getModuleById']);

// Question Routes
Route::get('/questions', [QuestionApiController::class, 'getQuestions']);
Route::get('/questions/{id}', [QuestionApiController::class, 'getQuestionById']);

// Quiz Routes
Route::get('/quizzes', [QuizApiController::class, 'getQuizzes']);
Route::get('/quizzes/{id}', [QuizApiController::class, 'getQuizById']);

// Score Routes
Route::get('/scores', [ScoreApiController::class, 'getScores']);
Route::get('/scores/{id}', [ScoreApiController::class, 'getScoreById']);

// User Module Routes
Route::get('/user_modules', [UserModuleApiController::class, 'getUserModules']);
Route::get('/user_modules/{id}', [UserModuleApiController::class, 'getUserModuleById']);

// Video Routes
Route::get('/videos', [VideoApiController::class, 'getVideos']);
Route::get('/videos/{id}', [VideoApiController::class, 'getVideoById']);
