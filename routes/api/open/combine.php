<?php

use App\Http\Controllers\Api\Combine\CombineController;
use Illuminate\Support\Facades\Route;

// users and projects
Route::get('/combine', [CombineController::class, 'combine']);
