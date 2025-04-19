<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ResultController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Course routes
    Route::apiResource('courses', CourseController::class);
    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll']);
    Route::post('courses/{course}/unenroll', [CourseController::class, 'unenroll']);

    // Quiz routes
    Route::apiResource('quizzes', QuizController::class);
    Route::get('quizzes/{quiz}/results', [QuizController::class, 'getStudentResults']);

    // Result routes
    Route::post('quizzes/{quiz}/results', [ResultController::class, 'store']);
    Route::get('results/user', [ResultController::class, 'getUserResults']);
    Route::get('quizzes/{quiz}/results/high-scores', [ResultController::class, 'getHighScores']);
    Route::get('quizzes/{quiz}/results/average', [ResultController::class, 'getAverageScore']);
});
