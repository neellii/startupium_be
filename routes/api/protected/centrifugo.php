<?php

use App\Http\Controllers\Api\Centrifugo\CentrifugoController;
use Illuminate\Support\Facades\Route;

// get connection token
Route::post('/centrifugo/connection_token', [CentrifugoController::class, 'getConnectionToken']);
