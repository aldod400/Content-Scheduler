<?php

use App\Http\Controllers\Api\PlatformController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'language'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout')
            ->middleware('auth:sanctum');
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [AuthController::class, 'getProfile'])->name('profile.get')
                ->middleware('auth:sanctum');
            Route::post('/', [AuthController::class, 'updateProfile'])->name('profile.update')
                ->middleware('auth:sanctum');
        });
    });
    Route::resource(
        'posts',
        PostController::class,
        ['only' => ['index', 'store', 'update', 'destroy']]
    )->middleware('auth:sanctum');
    Route::middleware('auth:sanctum')->prefix('platforms')->group(function () {
        Route::get('/', [PlatformController::class, 'index'])->name('platforms.index');
        Route::get('/my', [PlatformController::class, 'myPlatforms'])->name('platforms.my');
        Route::post('/toggle/{platformId}', [PlatformController::class, 'toggle'])->name('platforms.toggle');
    });
});
