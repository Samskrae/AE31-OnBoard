<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SpotController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API v1 Routes
Route::prefix('v1')->group(function () {
    // Spots resource routes
    Route::apiResource('spots', SpotController::class);
});
