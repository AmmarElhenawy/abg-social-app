<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        $comments = $post->comments()
                        ->with('user')
                        ->withCount('likes')
                        ->latest()
                        ->paginate(15);

        return response()->json($comments);
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
    // public function store(Request $request, Post $post)
    // {
    //     $validated = $request->validate([
    //         'content' => 'required|string|max:1000',
    //     ]);

    //     $user = Auth::user();
    //     $userId = $user->id;

    //     $comment = new Comment();
    //     $comment->user_id = $userId;
    //     $comment->post_id = $post->id;
    //     $comment->content = $validated['content'];
    //     $comment->save();

    //     return redirect()->back()
    //                     ->with('success', 'Comment added successfully!');
    // }

        public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $data['content'],
        ]);
        return back()->with('success', 'Comment added');
    }
    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {

        $user = Auth::user();
        $userId = $user->id;

        if ($comment->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->content = $validated['content'];
        $comment->save();

        return redirect()->back()
                        ->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {

        $user = Auth::user();
        $userId = $user->id;

        if ($comment->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return redirect()->back()
                        ->with('success', 'Comment deleted successfully!');
    }
}
