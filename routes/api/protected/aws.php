<?php

use App\Http\Controllers\Api\AWS\ImageUploadController;
use Illuminate\Support\Facades\Route;

// upload-image to aws
Route::post('/user/aws-image', [ImageUploadController::class, 'store']);
// read-image from aws
Route::get('/user/aws-image/{name}', [ImageUploadController::class, 'read']);
// delete-image from aws
Route::delete('/user/aws-image/{name}', [ImageUploadController::class, 'delete']);
