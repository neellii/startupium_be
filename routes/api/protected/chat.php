<?php

use App\Http\Controllers\Api\Chat\ChatController;
use App\Http\Controllers\Api\Chat\ChatReportController;
use Illuminate\Support\Facades\Route;

// user contacts
Route::get('/user/chat/contacts', [ChatController::class, 'getContacts']);
// add companion to contacts
Route::post('/user/chat/contacts', [ChatController::class, 'add']);
// remove companion from contacts
Route::delete('/user/chat/contacts', [ChatController::class, 'remove']);
// get messages between auth user and companion
Route::get('/user/chat/contacts/{contact}/messages', [ChatController::class, 'getMessagesFor']);
// create message to companion
Route::post('/user/chat/contacts/{contact}/messages', [ChatController::class, 'createMessage']);
// remove message
Route::delete('/user/chat/contacts/{contact}/messages/{message}', [ChatController::class, 'removeMessage']);
// update message
Route::put('/user/chat/contacts/{contact}/messages/{message}', [ChatController::class, 'updateMessage']);

// get messages ids from reports
Route::get('/user/chat/contacts/{contact}/messages-reports', [ChatReportController::class, 'reports']);
// add message to reports
Route::post('/user/chat/contacts/{contact}/messages/{message}/report', [ChatReportController::class, 'add']);
// add message from reports
Route::delete('/user/chat/contacts/{contact}/messages/{message}/report', [ChatReportController::class, 'remove']);

// unread messages count
Route::get('/user/chat/has-unread-messages', [ChatController::class, 'hasUnreadMessages']);
