<?php

use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LogController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\PlatformController;
use Illuminate\Support\Facades\Route;


Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');

Route::group(['as' => 'web.'], function () {
    Route::middleware(['guest', 'language'])->group(function () {
        Route::get('/login', [AuthController::class, 'loginView'])->name('view.login');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::get('/register', [AuthController::class, 'registerView'])->name('view.register');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
    });

    Route::middleware(['auth:sanctum', 'language'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [AuthController::class, 'getProfile'])->name('profile.get');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/filter', [DashboardController::class, 'filterPosts'])->name('dashboard.filter');
        Route::get('/dashboard/calendar', [DashboardController::class, 'getCalendarPosts'])->name('dashboard.calendar');

        Route::resources([
            'platforms' => PlatformController::class,
            'posts' => PostController::class,
        ]);
        Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    });
});
