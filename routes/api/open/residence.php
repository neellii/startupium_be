<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Residence\ResidenceController;

// countries
Route::get('/residence/countries', [ResidenceController::class, 'countries']);
// search countries
Route::get('/residence/countries/results', [ResidenceController::class, 'searchCountries']);
// // regions
// Route::get('/residence/regions', [ResidenceController::class, 'regions']);
// // search regions
// Route::get('/residence/regions/results', [ResidenceController::class, 'searchRegions']);
// cities
Route::get('/residence/cities', [ResidenceController::class, 'cities']);
// search cities
Route::get('/residence/cities/results', [ResidenceController::class, 'searchCities']);
