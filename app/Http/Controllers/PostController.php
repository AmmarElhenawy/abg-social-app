<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments'])
                    ->withCount(['likes', 'comments'])
                    ->latest();
                    // ->paginate(10);

        return view('dashboard', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'image' => 'nullable|image|max:2048', // 2MB
        ]);

        $user = Auth::user();
        $userId = $user->id;

        $post = new Post();
        $post->user_id = $userId;
        $post->content = $validated['content'];

        // رفع الصورة
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->route('dashboard')
                        ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'likes.user']);
        $post->loadCount(['likes', 'comments']);

        $comments = $post->comments()
                        ->with('user')
                        ->withCount('likes')
                        ->latest()
                        ->paginate(10);

        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $user = Auth::user();
        $userId = $user->id;

        //check authorization
    if ($post->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */

public function update(Request $request, Post $post)
    {
        $user = Auth::user();
        $userId = $user->id;
        if ($post->user_id !== $userId ) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);


        if ($request->has('remove_image') && $request->remove_image) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
                $post->image = null;
            }
        }


        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->content = $validated['content'];
        $post->save();

        return redirect()->route('posts.show', $post)
                        ->with('success', 'Post updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
public function destroy(Post $post)
    {
        $user = Auth::user();
        $userId = $user->id;
        if ($post->user_id !== $userId ) {
            abort(403, 'Unauthorized action.');
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('dashboard')
                        ->with('success', 'Post deleted successfully!');
    }
}
