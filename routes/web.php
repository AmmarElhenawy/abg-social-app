<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Models\Post;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// // Swagger UI
// Route::get('/docs', function () {
//     return view('l5-swagger::index');
// });

// // JSON API docs
// Route::get('/docs/api-docs.json', function () {
//     return response()->file(storage_path('api-docs/api-docs.json'));
// });
    // Search Routes
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/quick', [SearchController::class, 'quick'])->name('search.quick');

Route::middleware('auth')->group(function ()
{
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// users profiles
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/{user}', [ProfileController::class, 'show'])->name('profile');
        Route::get('/{user}/friends', [ProfileController::class, 'friends'])->name('friends');
        Route::get('/{user}/posts', [ProfileController::class, 'posts'])->name('posts');
    });

//post
    Route::resource('posts', PostController::class);

   // like
    Route::prefix('likes')->name('likes.')->group(function () {
        Route::post('/posts/{post}', [LikeController::class, 'togglePostLike'])->name('post.toggle');
        Route::post('/comments/{comment}', [LikeController::class, 'toggleCommentLike'])->name('comment.toggle');
        Route::get('/posts/{post}/users', [LikeController::class, 'getPostLikes'])->name('post.users');
        Route::get('/comments/{comment}/users', [LikeController::class, 'getCommentLikes'])->name('comment.users');
    });


   // comments
    Route::prefix('posts/{post}/comments')->name('comments.')->group(function () {
    Route::get('/', [CommentController::class, 'index'])->name('index');
    Route::post('/', [CommentController::class, 'store'])->name('store');
});
    Route::prefix('comments')->name('comments.')->group(function () {
    Route::patch('/{comment}', [CommentController::class, 'update'])->name('update');
    Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
    });

    Route::post('friends/{user}', [FriendController::class, 'send']);
    Route::post('friends/{user}/accept', [FriendController::class, 'accept']);

     // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Friend Requests
        Route::get('/friends', [FriendController::class, 'index'])->name('index');
        Route::get('/friends/requests', [FriendController::class, 'requests'])->name('requests');
        Route::post('/friends/{user}/send', [FriendController::class, 'send'])->name('send');
        Route::post('/friends/{user}/accept', [FriendController::class, 'accept'])->name('accept');
    Route::get('/friend-requests', [FriendController::class, 'requests'])->name('requests');
    Route::delete('/friends/{user}/cancel', [FriendController::class, 'cancel'])->name('cancel');
    Route::delete('/friends/{user}/reject', [FriendController::class, 'reject'])->name('reject');
    Route::delete('/friends/{user}/unfriend', [FriendController::class, 'unfriend'])->name('unfriend');


    });




require __DIR__.'/auth.php';



