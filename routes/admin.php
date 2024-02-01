<?php


use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'page'])->name('login.page');
    Route::post('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('posts', [\App\Http\Controllers\Admin\DashboardController::class, 'posts'])->name('posts.index');
    Route::put('posts/{post}', [\App\Http\Controllers\Admin\DashboardController::class, 'changeStatus'])->name('posts.make')->where('post', '[0-9]+');
    Route::get('users', [\App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users.index');
    Route::put('users/{user}/block', [\App\Http\Controllers\Admin\DashboardController::class, 'blockUser'])->name('users.block');
    Route::get('posts/popular', [\App\Http\Controllers\Admin\DashboardController::class, 'getMostPopularPosts'])->name('posts.popular');
});
