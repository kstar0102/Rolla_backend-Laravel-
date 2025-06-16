<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CarTypeController;
use App\Http\Controllers\Api\ImageUploadController;

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
    Route::get('all', [UserController::class, 'getAllUsers']);
    Route::put('update', [UserController::class, 'updateUserInfo']);
    Route::get('following_users', [UserController::class, 'getFollowingUsers']);
    Route::get('pending_following_users', [UserController::class, 'getPendingFollowingUsers']);
    Route::get('follwed_user/trips', [UserController::class, 'followedUserTrips']);
    Route::get('block_users', [UserController::class, 'getBlockUsers']);
    Route::get('block_user/trips', [UserController::class, 'getBlockUserTrips']);
    Route::post('droppin_like', [UserController::class, 'droppinLike']);
    Route::post('following', [UserController::class, 'followingUser']);;
    Route::get('followed_users', [UserController::class, 'followedUsers']);
    Route::post('block', [UserController::class, 'blockUser']);
    Route::post('removefollow', [UserController::class, 'removeFollowRequest']);
    Route::post('removeUserfollow', [UserController::class, 'removeUserFollow']);
    Route::post('requestfollow', [UserController::class, 'requestToFollowUser']);
    Route::post('acceptfollow', [UserController::class, 'acceptFollowRequest']);
    Route::post('block', [UserController::class, 'blockUser']);
    Route::delete('delete', [UserController::class, 'deleteUserAccount']);
});

// Trip routes
Route::group(['prefix' => 'trip'], static function () {
    Route::get('data', [TripController::class, 'getAllTrips']);
    Route::post('create', [TripController::class, 'createTrip']);
    Route::post('mute_user', [TripController::class, 'mutedUser']);
    Route::get('/trips/id', [TripController::class, 'getTripsByTripId']);
    Route::get('/trips/user', [TripController::class, 'getTripsByUserId']);
    Route::post('update', [TripController::class, 'updateTrip']);
    Route::post('delete', [TripController::class, 'removeTrip']);
});

// Droppin routes
Route::group(['prefix' => 'droppin'], static function () {
    Route::get('data', [TripController::class, 'getDroppins']);
    Route::post('viewed', [TripController::class, 'droppinViewed']);
});

Route::group(['prefix' => 'comment'], static function () {
    Route::post('create', [CommentController::class, 'createOrUpdateComment']);
});

Route::get('car_types', [CarTypeController::class, 'getCarTypes']);

Route::post('/upload-image', [ImageUploadController::class, 'upload']);

// Catch-all route for unknown endpoints
Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found.',
    ], 404);
});
