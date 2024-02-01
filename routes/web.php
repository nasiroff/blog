<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [\App\Http\Controllers\Site\HomeController::class, 'index'])->name('home');
Route::get('verify-email/{id}/{hash}', \App\Http\Controllers\Site\EmailVerifyController::class)
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');
Route::get('/posts/{id}', [\App\Http\Controllers\Site\PostController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('posts.show');

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [\App\Http\Controllers\Site\LoginController::class, 'index'])->name('login.page');
    Route::post('/login', [\App\Http\Controllers\Site\LoginController::class, 'login'])->name('login');
    Route::get('/register', [\App\Http\Controllers\Site\RegisterController::class, 'page'])->name('register.page');
    Route::post('/register', [\App\Http\Controllers\Site\RegisterController::class, 'register'])->name('register');
});


Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [\App\Http\Controllers\Site\LogoutController::class, 'logout'])->name('logout');
    Route::get("/posts/create", [\App\Http\Controllers\Site\PostController::class, 'create'])->name('posts.create');
    Route::post("/posts", [\App\Http\Controllers\Site\PostController::class, 'store'])->name('posts.store');
    Route::post("/posts/{post}/comments", [\App\Http\Controllers\Site\CommentController::class, 'store'])->name('posts.comments.store');
    Route::get('/chat', [\App\Http\Controllers\Site\HomeController::class, 'chat'])->name('chat');
});





