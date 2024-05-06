<?php

use App\Http\Controllers\Api\Settings\NotificationSettingsController;
use App\Http\Controllers\Api\Settings\PrivateSettingsController;
use App\Http\Controllers\Api\Settings\SendByEmailSettings;
use App\Http\Controllers\Api\User\CarrerController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\SkillController;
use App\Http\Controllers\Api\User\TechnologyController;
use Illuminate\Support\Facades\Route;

// profile info
Route::get('user/', [ProfileController::class, 'getProfile']); // add to swagger
// update profile
Route::put('user/', [ProfileController::class, 'updateProfileOld']); // add to swagger
// delete profile
Route::delete('user/', [ProfileController::class, 'deleteProfile']); // add to swagger
// set profile info
Route::post('user/', [ProfileController::class, 'postProfile']);
// get extended profile info
Route::get('profile/', [ProfileController::class, 'getExtenedProfile']);
//
Route::put('profile/person', [ProfileController::class, 'updatePerson']);
//
Route::put('profile/careers', [CarrerController::class, 'updateCareer']);
//
Route::post('profile/careers', [CarrerController::class, 'createCareer']);
//
Route::delete('profile/careers', [CarrerController::class, 'deleteCareer']);
//
Route::put('profile', [ProfileController::class, 'updateProfile']);
// profile settings
Route::get('user/profile-settings', [ProfileController::class, 'getProfileSettings']); // add to swagger
// update profile password
Route::put('user/change-password', [ProfileController::class, 'updatePassword']);
// check password and update email - переделать
Route::post('user/check-password', [ProfileController::class, 'checkPassword']);
// create password
Route::post('user/create-password', [ProfileController::class, 'createPassword']);
// upload avatar
//Route::post('user/upload-avatar', [ProfileController::class, 'uploadAvatar']);
// upload image
Route::post('user/upload-image', [ProfileController::class, 'uploadImage']);
// skills
Route::post('user/skills', [SkillController::class, 'createOrUpdateSkills']);
// technologies
Route::post('user/technologies', [TechnologyController::class, 'createOrUpdateTechnologies']);

// user Settings
Route::put('/user/sendByEmailSettings', [SendByEmailSettings::class, 'update']);
Route::put('/user/privateSettings', [PrivateSettingsController::class, 'update']);
Route::put('/user/notificationSettings', [NotificationSettingsController::class, 'update']);
