<?php

use App\Http\Controllers\Api\Comment\BlogCommentController;
use App\Http\Controllers\Api\Comment\CommentController;
use App\Http\Controllers\Api\Comment\ComplaintController;
use Illuminate\Support\Facades\Route;

// PROJECT COMMENTS
// create comment
Route::post('/comment', [CommentController::class, 'createComment']);
// update comment
Route::put('/comment/{comment}', [CommentController::class, 'updateComment']);
// delete comment
Route::delete('/comment/{comment}', [CommentController::class, 'deleteComment']);
// create comment reply
Route::post('/comment/{comment}/reply', [CommentController::class, 'createReply']);
// add comment to complaints
Route::put('/comments/{comment}/complaint', [ComplaintController::class, 'add']);
// delete comment from reports
//Route::delete('/comments/{comment}/report', [CommentReportController::class, 'delete']);


// BLOG COMMENTS
// create blog comment
Route::post('/blog-comments/{blog}', [BlogCommentController::class, 'createComment']);
// update blog comment
Route::put('/blog-comments/{comment}', [BlogCommentController::class, 'updateComment']);
// delete blog comment
Route::delete('/blog-comments/{comment}', [BlogCommentController::class, 'deleteComment']);
// create comment replies
Route::post('/blog-comments/{comment}/replies', [BlogCommentController::class, 'createReply']);
