<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ResultController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Student routes
    Route::middleware('role:student')->group(function () {
        Route::get('/courses/enrolled', [CourseController::class, 'enrolledCourses']);
        Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll']);
        Route::post('/courses/{course}/unenroll', [CourseController::class, 'unenroll']);
        Route::get('/quizzes/available', [QuizController::class, 'availableQuizzes']);
        Route::post('/quizzes/{quiz}/submit', [ResultController::class, 'store']);
        Route::get('/results/my', [ResultController::class, 'getUserResults']);
    });

    // Teacher routes
    Route::middleware('role:teacher')->group(function () {
        Route::apiResource('courses', CourseController::class);
        Route::apiResource('quizzes', QuizController::class);
        Route::get('/courses/{course}/students', [CourseController::class, 'students']);
        Route::get('/quizzes/{quiz}/results', [QuizController::class, 'getStudentResults']);
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [AuthController::class, 'index']);
        Route::get('/users/{user}', [AuthController::class, 'show']);
        Route::put('/users/{user}', [AuthController::class, 'update']);
        Route::delete('/users/{user}', [AuthController::class, 'destroy']);
        Route::get('/statistics', [AuthController::class, 'statistics']);
    });

    // Shared routes (accessible by all authenticated users)
    Route::get('/courses/public', [CourseController::class, 'publicCourses']);
    Route::get('/courses/{course}', [CourseController::class, 'show']);
});
