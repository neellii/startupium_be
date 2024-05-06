<?php

use App\Http\Controllers\Api\Feedback\FeedbackController;
use Illuminate\Support\Facades\Route;

// user feedback
Route::post('/user/feedback', [FeedbackController::class, 'createFeedback']);
