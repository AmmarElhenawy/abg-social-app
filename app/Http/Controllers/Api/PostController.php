<?php

namespace App\Http\Controllers\Api;

use App\Events\LikeUpdated;
use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class PostController extends BaseApiController
{


    public function index()
    {
        try {
            $posts = Post::with(['user', 'comments', 'likes'])->withCount(['likes', 'comments'])->latest()->get();
            return $this->success($posts);
        } catch (Throwable $e) {
            return $this->error('Something went wrong', 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'content' => 'required|string|max:5000',
                'image' => 'nullable|image|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('posts', 'public');
            }

            /** @var \App\Models\User $user */
            $user= Auth::user();
            if(!$user){
                return $this->error('Unauthenticated', 401);
            }
            $post = $user->posts()->create($data);

            return $this->success($post, 'Post created', 201);
        } catch (ValidationException $e) {
            return $this->error('Validation error', 422, $e->errors());
        } catch (Throwable $e) {
            return $this->error('Something went wrong', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try {
            $user=Auth::user();
            if(!$user){
                return $this->error('Unauthenticated', 401);
            }
            // $userId = $user->id;


            $post->load(['user', 'likes.user', 'comments.user']);
            $post->loadCount(['likes', 'comments']);
            return $this->success($post,'success',200);
        } catch (Throwable $e) {
            return $this->error('Something went wrong', 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    //check
    public function update(Request $request, Post $post)
    {
try {
        $user = Auth::user();
        if (!$user) {
            return $this->error('Unauthenticated', 401);
            }    $userId = $user->id;

    if ($post->user_id !== $userId) {
        return $this->error('You are not authorized to update this post', 403) ;
    }
    $data = $request->validate([
                'content' => 'required|string|max:5000'
            ]);

            $post->update($data);

    return $this->success($post, 'Post updated');
} catch (ValidationException $e) {
            return $this->error('Validation error', 422, $e->errors());
        } catch (Throwable $e) {
            return $this->error('Something went wrong', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post
    )
    {

        // try {
            $user = Auth::user();
            if (!$user) {
                return $this->error('Unauthenticated', 401);
            }
            // $userId = $user->id;

            // if ($post->user_id !== $userId) {
            //     return $this->error('You are not authorized to update this post', 403) ;
            // }
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->delete();

            return $this->success(null, 'Post deleted');

        // } catch (Throwable $e) {
        //     return $this->error('Something went wrong', 500);
        // }
    }

    public function toggleLike(Post $post)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->error('Unauthenticated', 401);
            }
            $like = Like::where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->first();

            if ($like) {
                $like->delete();
            } else {
                Like::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id
                ]);
            }

            $count = $post->likes()->count();
            broadcast(new LikeUpdated($post->id, $count))->toOthers();

            return $this->success(['likes' => $count]);
        } catch (Throwable $th) {
            return $this->error('Something went wrong', 500);
        }
    }
}

