<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
        public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->route('dashboard');
        }

        $user=Auth::user();
        $userId=$user->id;

        $users = User::where('id', '!=', $userId)
                    ->where(function($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%");
                    })
                    ->withCount('friends')
                    ->paginate(15);

        return view('search.index', compact('users', 'query'));
    }


    public function quick(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $user=Auth::user();
        $userId=$user->id;

        $users = User::where('id', '!=', $userId)
                    ->where('name', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get(['id', 'name', 'profile_picture']);

        return response()->json($users);
    }
}
