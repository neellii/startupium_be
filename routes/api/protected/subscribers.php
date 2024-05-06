<?php

use App\Http\Controllers\Api\Project\SubscriberController;
use Illuminate\Support\Facades\Route;

// заявки
Route::get('/project-subscribers/applications', [SubscriberController::class, 'applications']);
// кол-во заявок
Route::get('/project-subscribers/application-count', [SubscriberController::class, 'applicationCount']);
// участники
Route::get('/project-subscribers/members', [SubscriberController::class, 'members']);
// подать заявку
Route::post('/project-subscribers/subscribe', [SubscriberController::class, 'subscribe']);
// принять заявку
Route::put('/project-subscribers/subscribe', [SubscriberController::class, 'subscribed']);
// отклонить заявку
Route::delete('/project-subscribers/subscribe', [SubscriberController::class, 'unsubscribed']);
// обновить участника роль/специальность
Route::put('/project-subscribers/members', [SubscriberController::class, 'updateMember']);
// роли для участников
Route::get('/project-subscribers/roles', [SubscriberController::class, 'membersRoles']);
// fetch subscriber data
Route::get('/project-subscribers/subscriber', [SubscriberController::class, 'fetchSubscriber']);
// fetch all permissions to management
Route::get('/project-subscribers/permissions', [SubscriberController::class, 'fetchPermissions']);
