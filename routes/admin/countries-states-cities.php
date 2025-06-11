<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

Route::resource('countries', CountryController::class)->names('countries');
Route::resource('states', StateController::class)->names('states')->except(['show']);
Route::resource('cities', CityController::class)->names('cities');

Route::get('/cities-by-state/{stateId}', [CityController::class, 'getCitiesByState']);
Route::get('/states/{countryId}', [CityController::class, 'getStatesByCountry'])->name('states.byCountry');
Route::get('/states-by-country/{countryId}', [StateController::class, 'getStatesByCountry']);
