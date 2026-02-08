<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends BaseApiController
{


public function togglePostLike(Post $post)
{
    $user = Auth::user();
    if (!$user) {
        return $this->error('Unauthorized', 401);
    }

    $like = Like::where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->first();

    if ($like) {
        $like->delete();
        $liked = false;
        $message = 'Post unliked';
    } else {
        Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
        $liked = true;
        $message = 'Post liked';
    }

    $post->loadCount('likes');

    return $this->success([
        'liked' => $liked,
        'likes_count' => $post->likes_count
    ], $message, 200);
}


    /**
     * Like/Unlike
     */



public function getPostLikes(Post $post)
{
    try {
        $likes = Like::where('post_id', $post->id)
                    ->with('user:id,name,profile_picture')
                    ->latest()
                    ->paginate(20);

        return $this->success($likes->items());
    } catch (Exception $e) {
        return $this->error('Failed to fetch post likes', 500);
    }
}


}
