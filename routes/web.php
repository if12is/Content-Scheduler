<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Welcome Routes
Route::get('/', function () {
    return view('welcome');
});



Route::group(['middleware' => 'auth'], function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Post Management Routes
    Route::resource('posts', PostController::class);

    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/platform/{platform}', [SettingsController::class, 'updatePlatform'])->name('settings.platform.update');
    Route::delete('/settings/platform/{platform}', [SettingsController::class, 'disconnectPlatform'])->name('settings.platform.disconnect');
});

Auth::routes();
