<?php

use App\Http\Controllers\Api\Comment\BlogCommentController;
use App\Http\Controllers\Api\Comment\CommentController;
use Illuminate\Support\Facades\Route;

// PROJECT COMMENTS
// any project comments (роут для комментариев версии 1)
//Route::get('/comments/{project}', 'Comment\CommentController@projectComments');
// any project comments (роут для комментариев версии 2)
Route::get('/comments/{project}', [CommentController::class, 'getComments']); // add to swagger
// any comment replies (роут для комментариев версии 2)
Route::get('/replies/{comment}', [CommentController::class, 'getReplies']);
// any project comments count
Route::get('/comments/{project}/count', [CommentController::class, 'getCommentsCount']); // add to swagger


// BLOG COMMENTS
// any blog comments
Route::get('/blog-comments/{blog}', [BlogCommentController::class, 'getComments']);
// any comment replies
Route::get('/blog-comments/{comment}/replies', [BlogCommentController::class, 'getReplies']);
// any blog comments count
Route::get('/blog-comments/{blog}/count', [BlogCommentController::class, 'getCommentsCount']);
