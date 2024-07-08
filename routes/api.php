<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExternalApiController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/token', [ExternalApiController::class, 'getTokens']);
Route::get('/checkpoints', [ExternalApiController::class, 'getCheckpoints']);