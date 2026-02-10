<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\CommentController;
use App\Http\Controllers\Web\FeedController;
use App\Http\Controllers\web\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\web\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Web\FriendController as WebFriendController;
use App\Http\Controllers\web\LikeController as WebLikeController;
use App\Http\Controllers\Web\PostController as WebPostController;
use App\Http\Controllers\Web\ProfileController as WebProfileController;
use App\Http\Controllers\Web\SearchController as WebSearchController;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
// // Swagger UI
// Route::get('/docs', function () {
//     return view('l5-swagger::index');
// });

// // JSON API docs
// Route::get('/docs/api-docs.json', function () {
//     return response()->file(storage_path('api-docs/api-docs.json'));
// // });
//     // Search Routes
//     Route::get('/search', [SearchController::class, 'index'])->name('search.index');
//     Route::get('/search/quick', [SearchController::class, 'quick'])->name('search.quick');

// Route::middleware('auth')->group(function ()
// {
//     Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');


//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// // users profiles
//     Route::prefix('user')->name('user.')->group(function () {
//         Route::get('/{user}', [ProfileController::class, 'show'])->name('profile');
//         Route::get('/{user}/friends', [ProfileController::class, 'friends'])->name('friends');
//         Route::get('/{user}/posts', [ProfileController::class, 'posts'])->name('posts');
//     });

// //post
//     Route::resource('posts', PostController::class);

//    // like
//     Route::prefix('likes')->name('likes.')->group(function () {
//         Route::post('/posts/{post}', [LikeController::class, 'togglePostLike'])->name('post.toggle');
//         Route::post('/comments/{comment}', [LikeController::class, 'toggleCommentLike'])->name('comment.toggle');
//         Route::get('/posts/{post}/users', [LikeController::class, 'getPostLikes'])->name('post.users');
//         Route::get('/comments/{comment}/users', [LikeController::class, 'getCommentLikes'])->name('comment.users');
//     });


//    // comments
//     Route::prefix('posts/{post}/comments')->name('comments.')->group(function () {
//     Route::get('/', [CommentController::class, 'index'])->name('index');
//     Route::post('/', [CommentController::class, 'store'])->name('store');
// });
//     Route::prefix('comments')->name('comments.')->group(function () {
//     Route::patch('/{comment}', [CommentController::class, 'update'])->name('update');
//     Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
//     });

//     Route::post('friends/{user}', [FriendController::class, 'send']);
//     Route::post('friends/{user}/accept', [FriendController::class, 'accept']);

//      // Search
//     Route::get('/search', [SearchController::class, 'index'])->name('search');

//     // Friend Requests
//         Route::get('/friends', [FriendController::class, 'index'])->name('index');
//         Route::get('/friends/requests', [FriendController::class, 'requests'])->name('requests');
//         Route::post('/friends/{user}/send', [FriendController::class, 'send'])->name('send');
//         Route::post('/friends/{user}/accept', [FriendController::class, 'accept'])->name('accept');
//     Route::get('/friend-requests', [FriendController::class, 'requests'])->name('requests');
//     Route::delete('/friends/{user}/cancel', [FriendController::class, 'cancel'])->name('cancel');
//     Route::delete('/friends/{user}/reject', [FriendController::class, 'reject'])->name('reject');
//     Route::delete('/friends/{user}/unfriend', [FriendController::class, 'unfriend'])->name('unfriend');


//     });









########################




Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/', function () {
//     if (Auth::check()) {
//         return redirect()->route('home');
//         }
//         return redirect()->route('login');
//         });

        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        // Route::post('/logout', [AuthController::class, 'logout']);

// Route::middleware('auth')->group(function () {
//     Route::get('/home', [HomeController::class, 'index'])->name('home');
// });



Route::middleware('auth')->get('/feed', [FeedController::class, 'index'])->name('feed');;

// Route::middleware('auth')->post('/posts', [WebPostController::class, 'store']);


//user
Route::middleware('auth')->group(function () {
    Route::get('/users/{user}', [WebProfileController::class, 'show']);
    Route::get('/users/{user}/friends', [WebProfileController::class, 'friends']);
    Route::get('/users/{user}/posts', [WebProfileController::class, 'posts']);
    Route::get('/search', [WebProfileController::class, 'search']);
});

Route::middleware('auth')->get('/friends', [WebFriendController::class, 'index']);

Route::middleware('auth')->get('/search', function () {
    return view('search');
});
Route::middleware('auth')->post('/search', [WebSearchController::class, 'search']);






Route::middleware('auth')->group(function () {

    Route::resource('post', PostController::class);
    // Route::get('post', [PostController::class,'index']);

    Route::post('/posts/{post}/like',
        [PostController::class, 'toggleLike']
    )->name('post.like');
        Route::get('/posts/{post}/likes', [WebLikeController::class, 'getPostLikes'])
        ->name('posts.likes');
});

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments',
        [CommentController::class, 'store']
    )->name('comments.store');
});



//friends
Route::middleware('auth')->prefix('friends')->group(function () {

    Route::get('/', [FriendController::class, 'index'])
        ->name('friends.index');

    Route::get('/requests', [FriendController::class, 'requests'])
        ->name('friends.requests');

    Route::post('/{receiver}/send', [FriendController::class, 'sendRequest'])
        ->name('friends.send');

    Route::post('/{sender}/accept', [FriendController::class, 'acceptRequest'])
        ->name('friends.accept');

    Route::post('/{sender}/reject', [FriendController::class, 'rejectRequest'])
        ->name('friends.reject');

    Route::delete('/{user}', [FriendController::class, 'destroy'])
        ->name('friends.destroy');
});


Route::middleware('auth')->group(function () {

    Route::get('/profile', [WebProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/profile/edit', [WebProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [WebProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [WebProfileController::class, 'destroy'])
        ->name('profile.destroy');


    Route::get('/profile/friends/{user}', [ProfileController::class, 'friends'])->name('profile.friends');
    Route::get('/profile/posts/{user}', [ProfileController::class, 'posts'])->name('profile.posts');
    Route::get('/search', [WebSearchController::class, 'search'])->name('search');
});



Route::get('/{page}', 'App\Http\Controllers\AdminController@index');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
// Route::post('/register', [AuthenticatedSessionController::class, 'register']);
