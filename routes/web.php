<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Auth::routes();

Route::get('/', [PostController::class, 'publicIndex'])->name('home');
Route::get('/tag/{tag}', [TagController::class, 'show'])->name('tag.show');
Route::get('/post/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/post/{post}/unlock', [PostController::class, 'unlock'])->name('posts.unlock');

Route::middleware('auth')->group(function () {
    Route::get('/feed', FeedController::class)->name('feed');
    Route::get('/people', [FollowController::class, 'index'])->name('people');
    Route::post('/follow/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');

    Route::get('/my/post/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/my/post',       [PostController::class, 'store'])->name('posts.store');
    Route::get('/my/post/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/my/post/{post}',      [PostController::class, 'update'])->name('posts.update');
    Route::delete('/my/post/{post}',   [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/post/{post}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth'])->name('dashboard');
});
require __DIR__.'/auth.php';