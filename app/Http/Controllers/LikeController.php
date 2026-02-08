<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        //
    }

    public function togglePostLike(Post $post)
    {

        $user=Auth::user();
        $userId=$user->id;
        $like = Like::where('user_id', $userId)
                    ->where('likeable_type', Post::class)
                    ->where('likeable_id', $post->id)
                    ->first();

        if ($like) {
            // إذا كان موجود، احذفه (Unlike)
            $like->delete();
            $message = 'Post unliked';
        } else {
            // إذا مش موجود، اعمل لايك
            Like::create([
                'user_id' => $userId,
                'likeable_type' => Post::class,
                'likeable_id' => $post->id,
            ]);
            $message = 'Post liked';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * تبديل اللايك للتعليق (Like/Unlike)
     */
    public function toggleCommentLike(Comment $comment)
    {

        $user=Auth::user();
        $userId=$user->id;
        $like = Like::where('user_id', $userId)
                    ->where('likeable_type', Comment::class)
                    ->where('likeable_id', $comment->id)
                    ->first();

        if ($like) {
            $like->delete();
            $message = 'Comment unliked';
        } else {
            Like::create([
                'user_id' => $userId,
                'likeable_type' => Comment::class,
                'likeable_id' => $comment->id,
            ]);
            $message = 'Comment liked';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * عرض قائمة المستخدمين اللي عملوا لايك للبوست
     */
    public function getPostLikes(Post $post)
    {
        $likes = $post->likes()
                    ->with('user')
                    ->latest()
                    ->get();

        // يمكن إرجاع JSON للـ API أو View للـ Web
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $likes
            ]);
        }

        return view('posts.likes', compact('post', 'likes'));
    }

    /**
     * عرض قائمة المستخدمين اللي عملوا لايك للتعليق
     */
    public function getCommentLikes(Comment $comment)
    {
        $likes = $comment->likes()
                        ->with('user')
                        ->latest()
                        ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $likes
            ]);
        }

        return view('comments.likes', compact('comment', 'likes'));
    }
}

