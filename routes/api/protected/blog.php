<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\BlogController;
use App\Http\Controllers\Api\Blog\BlogFavoriteController;

// FOR PROJECTS

// delete blog
Route::delete('/projects/{project}/blogs/{blog}', [BlogController::class, 'deleteForProject']);
// drafts
Route::get('/projects/{project}/drafts', [BlogController::class, 'projectDrafts']);
// publish project draft
Route::put('/projects/{project}/drafts', [BlogController::class, 'publishProjectDraft']);


// FOR USERS

// delete blog
Route::delete('/users/{user}/blogs/{blog}', [BlogController::class, 'deleteForUser']);
// drafts
Route::get('/users/{user}/drafts', [BlogController::class, 'userDrafts']);
// publish user draft
Route::put('/users/{user}/drafts', [BlogController::class, 'publishUserDraft']);


// OTHER

// add to favorites
Route::post('/blogs/favorite', [BlogFavoriteController::class, 'addToFavorites']);
// delete from favorites
Route::delete('/blogs/favorite', [BlogFavoriteController::class, 'deleteFromFavorites']);
// get blog credentials
Route::get('blogs/{blog}/credentials', [BlogController::class, 'blogCredentials']);
// create blog
Route::post('/blogs', [BlogController::class, 'createBlog']);
// create blog
Route::put('/blogs', [BlogController::class, 'updateBlog']);
// create blog
Route::post('/blogs/drafts', [BlogController::class, 'createDraft']);

