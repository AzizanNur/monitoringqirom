<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\linkGlobalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::get('user', [AuthController::class, 'user']);
Route::post('verify_token', [AuthController::class, 'verify_token']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('all_link', [linkGlobalController::class, 'all_link']);
    Route::post('add_link', [linkGlobalController::class, 'add_link']);
    Route::get('find_one/{id}', [linkGlobalController::class, 'find_one']);
    Route::post('update/{id}', [linkGlobalController::class, 'update']);
    Route::post('delete/{id}', [linkGlobalController::class, 'delete']);
});
