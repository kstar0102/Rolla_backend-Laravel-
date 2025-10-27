<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CarTypeController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\TestController;

Route::get('/_mailtest', function (Request $req) {
    $fromAddress = config('mail.from.address');
    $fromName    = config('mail.from.name');

    $to = $req->query('to', 'seniordev52@gmail.com');

    // basic validation to avoid null/invalid addresses
    if (!is_string($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return response("Invalid 'to' email: ".print_r($to, true), 422);
    }
    if (!is_string($fromAddress) || !filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
        return response("Invalid 'from' email in config: ".print_r($fromAddress, true), 500);
    }

    try {
        Mail::raw('Test email from Laravel', function ($m) use ($to, $fromAddress, $fromName) {
            $m->from($fromAddress, $fromName);
            $m->to($to)->subject('Test');
        });
        return "sent to {$to} from {$fromAddress}";
    } catch (TransportExceptionInterface $e) {
        return response('MAIL ERROR: ' . $e->getMessage(), 500);
    } catch (\Throwable $e) {
        return response('GENERAL ERROR: ' . $e->getMessage(), 500);
    }
});


// Route::apiResource('tests', TestController::class);

Route::group(['prefix' => 'tests'], static function () {
    Route::post('store', [TestController::class, 'store']);
    Route::get('{id}', [TestController::class, 'show']);
    Route::get('latest',  [TestController::class, 'latestRecord']);
});

// Auth routes
Route::group(['prefix' => 'auth'], static function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);   
    Route::post('/password/verify', [AuthController::class, 'verifyResetCode']);  
    Route::post('/password/reset',  [AuthController::class, 'resetPassword']); 
});

// User routes
Route::group(['prefix' => 'user'], static function () {
    Route::get('info', [UserController::class, 'getUserInfo']);
    Route::get('all', [UserController::class, 'getAllUsers']);
    Route::put('update', [UserController::class, 'updateUserInfo']);
    Route::get('following_users', [UserController::class, 'getFollowingUsers']);
    Route::get('pending_following_users', [UserController::class, 'getPendingFollowingUsers']);
    Route::get('notification_users', [UserController::class, 'getNotificationUsers']);
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
    Route::post('accpetViewed', [UserController::class, 'markFollowNotificationAsSent']);
    Route::post('tapviewed', [UserController::class, 'markTagNotificationAsRead']);
    Route::post('commentviewed', [UserController::class, 'markCommentNotificationAsRead']);
    Route::post('tapfollowedUser', [UserController::class, 'closeFollowedUser']);
    
    Route::post('viewedlikenotification', [UserController::class, 'viewedLikeNotification']);
    Route::post('viewedcommentnotification', [UserController::class, 'viewedCommentNotification']);
    Route::post('viewedtagnotification', [UserController::class, 'viewedTagNotification']);
    Route::post('viewedfollowingnotification', [UserController::class, 'viewedFollowingNotification']);
    Route::post('viewedfollowPendingnotification', [UserController::class, 'viewedFollowPendingNotification']);
    Route::post('viewedfollowednotification', [UserController::class, 'viewedFollowedNotification']);

    Route::post('clickedFollowingNotification', [UserController::class, 'clickedFollowingNotification']);
    Route::post('clickedCommentNotification', [UserController::class, 'clickedCommentNotification']);
    Route::post('clickedLikeNotification', [UserController::class, 'clickedLikeNotification']);
    Route::post('clickedTagNotification', [UserController::class, 'clickedTagNotification']);
    Route::post('clickedFollowedNotification', [UserController::class, 'clickedFollowedNotification']);
    Route::post('clickedFollowPendingNotification', [UserController::class, 'clickedFollowPendingNotification']);

    Route::post('likedviewed', [UserController::class, 'markLikeNotificationAsRead']);
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
    Route::post('/droppins/format/portrait', [TripController::class, 'setAllPortrait']);

});

// Droppin routes
Route::group(['prefix' => 'droppin'], static function () {
    Route::get('data', [TripController::class, 'getDroppins']);
    Route::post('viewed', [TripController::class, 'droppinViewed']);
    Route::post('withnumberviewed', [TripController::class, 'droppinViewedwithnumber']);
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
