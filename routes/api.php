<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CenterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExamResultController;
use App\Http\Controllers\Api\ExamSessionController;
use App\Http\Controllers\Api\ExamResultImportController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

Route::middleware(['auth:sanctum','role:super_admin'])->group(function () {

    Route::get('/secretaries', [UserController::class, 'index']);

    Route::get('/secretaries/{user}', [UserController::class, 'show']);

    Route::post('/secretaries', [UserController::class, 'createSecretary']);

    Route::put('/secretaries/{user}', [UserController::class, 'update']);

    Route::delete('/secretaries/{user}', [UserController::class, 'destroy']);

    Route::post( '/secretaries/{user}/reset-password',  [UserController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum','role:super_admin'])->group(function () {

    Route::get('/centers', [CenterController::class, 'index']);

    Route::post('/centers', [CenterController::class, 'store']);

    Route::get('/centers/{center}', [CenterController::class, 'show']);

    Route::put('/centers/{center}', [CenterController::class, 'update']);

    Route::delete('/centers/{center}', [CenterController::class, 'destroy']);

    Route::patch( '/centers/{center}/toggle-status', [CenterController::class, 'toggleStatus']);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/exam-sessions', [ExamSessionController::class, 'index']);

    Route::post('/exam-sessions', [ExamSessionController::class, 'store']);

    Route::get('/exam-sessions/{examSession}', [ExamSessionController::class, 'show']);

    Route::put('/exam-sessions/{examSession}', [ExamSessionController::class, 'update']);

    Route::delete('/exam-sessions/{examSession}', [ExamSessionController::class, 'destroy']);

    Route::patch('/exam-sessions/{examSession}/toggle-publication', [ ExamSessionController::class,'togglePublication', ]);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/exam-sessions/{session}/import-results', [ExamResultImportController::class, 'import']);
});


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/exam-results', [ExamResultController::class, 'index']);

    Route::post('/exam-results', [ExamResultController::class, 'store']);

    Route::get('/exam-results/{examResult}', [ExamResultController::class, 'show']);

    Route::put('/exam-results/{examResult}', [ExamResultController::class, 'update']);

    Route::delete('/exam-results/{examResult}', [ExamResultController::class, 'destroy']);

});


use App\Http\Controllers\Api\PublicResultController;

Route::get('/public/results/search', [PublicResultController::class, 'search']);