<?php

use App\Http\Controllers\Api\User\NotificationController;
use Illuminate\Support\Facades\Route;

// all profile notifications
Route::get('user/notifications', [NotificationController::class, 'getNotifications']);
// remove all profile notifications
Route::delete('user/notifications', [NotificationController::class, 'removeNotifications']);
// profile unread notifications
Route::get('user/unread-notifications', [NotificationController::class, 'getUnreadNotifications']);
// make notifications read
Route::get('user/make-notifications-read', [NotificationController::class, 'makeNotificationsRead']);
// make notifications read by ID
Route::put('/make-notifications-read', [NotificationController::class, 'makeNotificationsReadById']);
// make Message Notifications Read
Route::get('user/make-message-notifications-read', [NotificationController::class, 'makeMessageNotificationsRead']);
// has unread notifications
Route::get('user/has-unread-notifications', [NotificationController::class, 'hasUnreadNotifications']);
