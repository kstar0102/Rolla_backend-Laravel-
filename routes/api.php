<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

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

// Auth routes
Route::group(['prefix' => 'auth'], static function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

// User routes
Route::group(['prefix' => 'user'], static function () {
    Route::get('info', [UserController::class, 'getUserInfo']);
    Route::put('update', [UserController::class, 'updateUserInfo']);
    Route::delete('delete', [UserController::class, 'deleteUserAccount']);
});

// Catch-all route for unknown endpoints
Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found.',
    ], 404);
});
