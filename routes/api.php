<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SellersController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

Route::resource('clients', ClientController::class);
Route::get('/sellers-by-client/{clientId}', [ClientController::class, 'getSellersByClientCity']);

Route::resource('sellers', SellersController::class);
Route::get('/sellers-by-city/{cityId}', [SellersController::class, 'getSellersByCity']);
Route::get('cities', [CityController::class, 'index']);

Route::resource('cities', CityController::class);

Route::get('countries', [CountryController::class, 'index']);
Route::get('states', [StateController::class, 'index']);

