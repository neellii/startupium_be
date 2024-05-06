<?php

use App\Http\Controllers\Api\Blog\BlogController;
use Illuminate\Support\Facades\Route;

// ALL BLOGS
// blogs
Route::get('blogs', [BlogController::class, 'blogs']);


// FOR PROJECT

// blogs
Route::get('projects/{project}/blogs', [BlogController::class, 'projectBlogs']);
// any blog
Route::get('projects/{project}/blogs/{blog}', [BlogController::class, 'anyProjectBlog']);


// FOR USERS

// blogs
Route::get('users/{user}/blogs', [BlogController::class, 'userBlogs']);
// any blog
Route::get('users/{user}/blogs/{blog}', [BlogController::class, 'anyUserBlog']);
