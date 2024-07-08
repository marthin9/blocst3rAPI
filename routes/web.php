<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExternalApiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tokens', [ExternalApiController::class, 'getTokens']);
Route::post('/checkpoints', [ExternalApiController::class, 'getCheckpoints']);