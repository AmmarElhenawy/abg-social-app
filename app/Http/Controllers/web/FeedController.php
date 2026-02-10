<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')
            ->withCount(['likes','comments'])
            ->latest()
            ->get();

        return view('feed.index', compact('posts'));
    }
}
