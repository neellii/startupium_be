<?php

use App\Http\Controllers\Api\Project\SubscriberController;
use Illuminate\Support\Facades\Route;

// project subscribers
Route::get('/project-subscribers', [SubscriberController::class, 'subscribers']);
