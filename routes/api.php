<?php

use App\Http\Controllers\Api\ServerApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/server/update', [ServerApiController::class, 'update']);
Route::get('/server/config', [ServerApiController::class, 'getConfig']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
