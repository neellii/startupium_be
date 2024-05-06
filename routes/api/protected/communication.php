<?php

use App\Http\Controllers\Api\Chat\CommunicationController;
use Illuminate\Support\Facades\Route;

// messages
Route::get('/user/communication/messages', [CommunicationController::class, 'messages']);
// send message
Route::post('/user/communication/messages', [CommunicationController::class, 'sendMessage']);
