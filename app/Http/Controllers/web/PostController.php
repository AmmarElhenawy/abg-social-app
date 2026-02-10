<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
       public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'likes']);
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:5000',
        ]);
        if ($request->hasFile('image')) {
    $data['image'] = $request->file('image')->store('posts', 'public');

    }


/** @var \App\Models\User $user */
        $user=Auth::user();
        $user->posts()->create($data);

        return back();
    }


    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'content' => 'required|string|max:5000'
        ]);

        $post->update($data);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted');
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        $like = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        $like ? $like->delete() : Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        return back();
    }
}
