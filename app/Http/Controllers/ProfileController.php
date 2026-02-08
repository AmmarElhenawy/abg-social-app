<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function friends(User $user)
    {
        $userAuth = Auth::user();
        $userId = $userAuth->id;

        $friends = $user->friends()
                        ->withCount('friends')
                        ->paginate(12);

        $pendingRequests = collect();
        if ($user->id === $userId) {
            $pendingRequests = Friend::where('friend_id', $userId)
                                        ->where('status', 'pending')
                                        ->with('sender')
                                        ->get();
        }

        return view('user.friends', compact('user', 'friends', 'pendingRequests'));
    }

    /**
     * عرض بوستات مستخدم محدد
     */
    public function posts(User $user)
    {
        $posts = $user->posts()
                    ->with(['user', 'likes', 'comments'])
                    ->withCount(['likes', 'comments'])
                    ->latest()
                    ->paginate(15);

        return view('user.posts', compact('user', 'posts'));
    }

    }
