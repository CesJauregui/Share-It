<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [AuthController::class, 'index']);
    Route::get('auth/logout', [AuthController::class, 'logout']);
});
