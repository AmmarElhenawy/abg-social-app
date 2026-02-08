<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

	// Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);


    // Users (profile)
    Route::prefix('users')->group(function () {
        Route::get('/search', [ProfileController::class, 'search']);
        Route::get('/{user}', [ProfileController::class, 'show']);
        Route::patch('/profile', [ProfileController::class, 'update']);
        Route::post('/profile/image', [ProfileController::class, 'uploadProfileImage']);
        Route::get('/{user}/friends', [ProfileController::class, 'friends']);
        Route::get('/{user}/posts', [ProfileController::class, 'posts']);
    });





    Route::prefix('posts')->group(function () {
        // Posts
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::get('/{post}', [PostController::class, 'show']);
        Route::patch('/{post}', [PostController::class, 'update']);
        Route::delete('/{post}', [PostController::class, 'destroy']);


        // Likes
        Route::post('{post}/like', [LikeController::class, 'togglePostLike']);
        Route::get('{post}/likes', [LikeController::class, 'getPostLikes']);
        // Comments
        Route::get('/{post}/comments', [CommentController::class, 'index']);
        Route::post('/{post}/comments', [CommentController::class, 'store']);
        Route::get('{id}/comment', [CommentController::class, 'show']);
        });



    // Friends (Connections)
    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendController::class, 'index']);
        Route::get('/requests', [FriendController::class, 'requests']);
        Route::post('/{receiver}/send', [FriendController::class, 'sendRequest']);
        Route::post( '/{sender}/accept', [FriendController::class, 'acceptRequest']);
        Route::post('/{sender}/reject', [FriendController::class, 'rejectRequest']);
        Route::delete('/{receiver}', [FriendController::class, 'destroy']);
    });


});

