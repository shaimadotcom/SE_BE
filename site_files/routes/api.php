<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'users'], function () {
    Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\UserController::class, 'register']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/get-data', [\App\Http\Controllers\UserController::class, 'getData']);
    Route::get('/get-users-data', [\App\Http\Controllers\UserController::class, 'getUsersData']);
    Route::get('/get-user-data', [\App\Http\Controllers\UserController::class, 'getUserData']);
    Route::group(['prefix' => 'users'], function () {
        Route::post('/update', [\App\Http\Controllers\UserController::class, 'update']);
        Route::post('/save-progress', [\App\Http\Controllers\UserController::class, 'saveProgress']);
    });

});
