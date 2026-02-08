<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentCreated;
use App\Http\Controllers\Controller;
use App\Models\Comment ;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;
use Throwable;

class CommentController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        try {
            $comments = $post->comments()->with('user')->latest()->paginate(15);
            return $this->success($comments->items());
        } catch (Exception $th) {
                        return $this->error('Failed to fetch comments', 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        try {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
            if (!$user) {
                return $this->error('Unauthorized', 401);
            }
        $userId=$user->id;
        $comment = $post->comments()->create([
            'user_id' => $userId,
            'content' => $data['content']
        ]);

        broadcast(new CommentCreated($comment))->toOthers();

        return $this->success($comment, 'Comment added', 201);

            } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        } catch (Exception $e) {
            return $this->error('Failed to add comment', 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $comment = Comment::with('user')->findOrFail($id);
            return $this->success($comment);
        } catch (ModelNotFoundException $e) {
            return $this->error('Comment not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to fetch comment', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Comment $comment)
    {

    }

}
