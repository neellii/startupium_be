<?php

use App\Http\Controllers\Api\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// user logout
Route::get('/logout', [LoginController::class, 'logout']);
