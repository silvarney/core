<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API for Astro
Route::group(['prefix' => 'v1', 'middleware' => 'throttle:api'], function () {
    // Properties
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{property}', [PropertyController::class, 'show']);

    // Bookings & Quotes
    Route::post('/bookings/quote', [BookingController::class, 'quote']);
    Route::post('/bookings', [BookingController::class, 'store']);
});
