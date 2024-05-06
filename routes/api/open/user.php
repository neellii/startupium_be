<?php

use App\Http\Controllers\Api\Search\SearchController;
use App\Http\Controllers\Api\User\SkillController;
use App\Http\Controllers\Api\User\TechnologyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserController;

// active users
Route::get('/users', [UserController::class, 'getUsers']); // add to swagger
// any user info
Route::get('/users/{user}', [UserController::class, 'getAnyUser']); // add to swagger
// any user skills
Route::get('/skills/{user}', [SkillController::class, 'getAnyUserSkills']); // add to swagger
// any user technologies
Route::get('/technologies/{user}', [TechnologyController::class, 'getAnyUserTechnologies']); // add to swagger
// search users
Route::get('/user-results', [SearchController::class, 'searchUsers']);
