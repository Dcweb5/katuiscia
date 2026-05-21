<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Controllers\ProfileController;
use App\Modules\Auth\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register',             [AuthController::class, 'register']);
Route::post('/register/full',        [AuthController::class, 'registerFull']);
Route::post('/login',                [AuthController::class, 'login']);
Route::post('/forgot-password',      [AuthController::class, 'forgotPassword']);
Route::post('/reset-password',       [AuthController::class, 'resetPassword']);

// Routes sociales
Route::get('/auth/{provider}',        [SocialiteController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',           [AuthController::class, 'logout']);
    Route::get('/user',              [AuthController::class, 'user']);
    Route::put('/user/profile',      [ProfileController::class, 'update']);
    Route::put('/user/password',     [ProfileController::class, 'changePassword']);
    Route::delete('/user',           [ProfileController::class, 'destroy']);
});
