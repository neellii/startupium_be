<?php

use App\Http\Controllers\Api\Project\FavoriteController;
use App\Http\Controllers\Api\Project\ProjectController;
use App\Http\Controllers\Api\Project\SubscriberController;
use App\Http\Controllers\Api\Search\SearchController;
use Illuminate\Support\Facades\Route;

// all projects
Route::get('/projects', [ProjectController::class, 'getProjects']); // add to swagger
// any project info
Route::get('/projects/{project}', [ProjectController::class, 'getAnyProject']); // add to swagger
// any project meta info
Route::get('/projects/{project}/meta', [ProjectController::class, 'getAnyMetaProject']);
// project subscribers
Route::get('/projects/{project}/subscribers', [SubscriberController::class, 'getProjectSubscribers']);
// any project favorites total
Route::get('/favorites/{project}/total', [FavoriteController::class, 'getProjectFavoritesCount']);
// search projects
Route::get('/project-results', [SearchController::class, 'searchProjects']); // add to swagger
// popular projects
Route::get('/popular-projects', [ProjectController::class, 'popularProjectsIds']); // add to swagger
