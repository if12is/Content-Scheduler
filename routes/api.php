<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PlatformController;
use App\Http\Controllers\Api\ActivityLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Post routes
    Route::apiResource('posts', PostController::class);

    // Platform routes
    Route::get('/platforms', [PlatformController::class, 'index']);
    Route::get('/user/platforms', [PlatformController::class, 'userPlatforms']);
    Route::post('/platforms/{platform}/toggle', [PlatformController::class, 'toggle']);

    // Activity log routes
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    Route::get('/activity-stats', [ActivityLogController::class, 'stats']);
});
