<?php

use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\YandexLoginController;
use Illuminate\Support\Facades\Route;

// yandex callback
Route::post('/yandex/signIn', [YandexLoginController::class, 'handleProviderCallback']);

// email verification
Route::get('/verification/{code}', [VerificationController::class, 'verification']);
// password forgot
Route::post('/password/recovery', [ForgotPasswordController::class, 'forgotPassword']);
// check token
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'checkToken']);
// password reset
Route::post('/password/reset/{token}', [ForgotPasswordController::class, 'resetPassword']);
// email resend
Route::post('/resend', [VerificationController::class, 'resend']);
// user register
Route::post('/register', [RegisterController::class, 'register']);
// user login
Route::post('/login', [LoginController::class, 'login']);
// user refresh token
Route::post('/refresh', [LoginController::class, 'refresh']);
