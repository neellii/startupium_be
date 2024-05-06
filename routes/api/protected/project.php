<?php

use App\Http\Controllers\Api\Project\BookmarkController;
use App\Http\Controllers\Api\Project\ComplaintController;
use App\Http\Controllers\Api\Project\DraftController;
use App\Http\Controllers\Api\Project\FavoriteController;
use App\Http\Controllers\Api\Project\RequireForTeamController;
use App\Http\Controllers\Api\Project\SubscriberController;
use App\Http\Controllers\Api\User\ProjectController;
use Illuminate\Support\Facades\Route;

// profile projects
Route::get('user/projects', [ProjectController::class, 'getProfileProjects']);
// create project
Route::post('user/projects', [ProjectController::class, 'createProject']);
// delete project
Route::delete('user/projects/{project}', [ProjectController::class, 'deleteProject']);
// update project
Route::put('user/projects/{project}', [ProjectController::class, 'updateProject']);
// leave project
Route::post('user/projects/{project}', [ProjectController::class, 'leaveProject']);
// move project to drafts
Route::put('user/projects/{project}/onDraft', [ProjectController::class, 'onDraft']);
Route::put('user/projects/{project}/onDraftSimple', [ProjectController::class, 'onDraftSimple']);
// projects in bookmarks
Route::get('/user/bookmarks', [BookmarkController::class, 'getUserBookmarks']);
//  projects in drafts
Route::get('/user/drafts', [DraftController::class, 'getUserDrafts']);
// create draft
Route::post('/user/drafts', [DraftController::class, 'createDraft']);
// move draft to moderation
Route::put('/user/drafts/{project}/onModeration', [DraftController::class, 'onModeration']);

// project has in favorites
Route::get('/favorites/{project}/favorite', [FavoriteController::class, 'hasInFavorites']);
// add to favorites
Route::post('/favorites/{project}/favorite', [FavoriteController::class, 'add']);
// remove from favorites
Route::delete('/favorites/{project}/favorite', [FavoriteController::class, 'remove']);

// project has in bookmarks
Route::get('/bookmarks/{project}/bookmark', [BookmarkController::class, 'hasInBookmarks']);
// add to bookmarks
Route::post('/bookmarks/{project}/bookmark', [BookmarkController::class, 'add']);
// remove from bookmarks
Route::delete('/bookmarks/{project}/bookmark', [BookmarkController::class, 'remove']);

// add to complaints
Route::post('/projects/{project}/complaint', [ComplaintController::class, 'add']);
// remove from complaints
//Route::delete('/projects/{project}/complaint', [ComplaintController::class, 'delete']);

// require for team - positions
Route::get('projects/{project}/require-for-team', [RequireForTeamController::class, 'positions']);
// require for team - create position
Route::post('projects/{project}/require-for-team', [RequireForTeamController::class, 'create']);
// require for team - update position
Route::put('projects/{project}/require-for-team', [RequireForTeamController::class, 'update']);
// require for team - delete position
Route::delete('projects/{project}/require-for-team', [RequireForTeamController::class, 'delete']);
// require for team - delete position
Route::put('projects/{project}/require-for-team/switch', [RequireForTeamController::class, 'switchTag']);

// signed projects
Route::get('user/signed-projects', [SubscriberController::class, 'signedProjects']);
// title of projects
Route::get('/user/projects/title-of-projects', [ProjectController::class, 'titleOfProjects']);
