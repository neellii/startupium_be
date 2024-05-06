<?php

use App\Http\Controllers\Api\Wiki\ArticleController;
use App\Http\Controllers\Api\Wiki\CombineController;
use App\Http\Controllers\Api\Wiki\SectionController;
use Illuminate\Support\Facades\Route;

// get combine data (sections and articles)
Route::get('/user/management/wiki-combine/{project}', [CombineController::class, 'getCombine']);

// get user sections
Route::get('/user/management/wiki-sections/{project}', [SectionController::class, 'getSections']);
// create section
Route::post('/user/management/wiki-sections/{project}', [SectionController::class, 'create']);
// delete section
Route::delete('/user/management/wiki-sections/{project}', [SectionController::class, 'delete']);
// update section
Route::put('/user/management/wiki-sections/{project}', [SectionController::class, 'update']);

// get user articles
Route::get('/user/management/wiki-articles/{project}', [ArticleController::class, 'getArticles']);
// create article
Route::post('/user/management/wiki-articles/{project}', [ArticleController::class, 'create']);
// create article copy
Route::post('/user/management/wiki-articles-copy/{project}', [ArticleController::class, 'createCopy']);
// update article
Route::put('/user/management/wiki-articles/{project}', [ArticleController::class, 'update']);
// to default article
//Route::put('/user/management/wiki-article-default/{project}', [ArticleController::class, 'addToDefault']);
// get default article
Route::get('/user/management/wiki-article-default/{project}', [ArticleController::class, 'defaultArticle']);
// delete article
Route::delete('/user/management/wiki-articles/{project}', [ArticleController::class, 'delete']);
