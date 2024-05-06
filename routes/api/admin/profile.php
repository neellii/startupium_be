<?php

use App\Http\Controllers\Api\Admin\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

// user clear position
Route::get('/admin/user/clear-position', [ProfileController::class, 'clearPosition']);
