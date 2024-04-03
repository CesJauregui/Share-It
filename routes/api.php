<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [AuthController::class, 'index']);

    Route::get('post/get-posts', [PostController::class, 'getPosts']);
    Route::post('post/create-post', [PostController::class, 'createPost']);
    Route::get('post/get-post/{id}', [PostController::class, 'getPostById']);
    Route::put('post/update-post/{id}', [PostController::class, 'updatePost']);
    Route::delete('post/delete-post/{id}', [PostController::class, 'deletePost']);
    Route::get('post/get-post-type', [PostController::class, 'getPostType']);
    Route::post('post/create-post-type', [PostController::class, 'createPostType']);
    Route::get('post/count-posts', [PostController::class, 'countPost']);

    Route::delete('logout', [AuthController::class, 'logout']);
});
