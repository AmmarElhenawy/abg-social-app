<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show( )
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        $user->loadCount(['posts','friends']);
        $posts = $user->posts()->latest()->get();

        return view('profile.show', compact('user','posts'));
    }
    public function friends(User $user)
    {
        $friends = $user->friends;
        return view('profile.friends', compact('user','friends'));
    }

    public function posts(User $user)
    {
        $posts = $user->posts()->withCount(['likes','comments'])->latest()->get();
        return view('profile.posts', compact('user','posts'));
    }

    public function search(Request $request)
    {
        $users = User::where('name','like','%'.$request->q.'%')->get();
        return view('search', compact('users'));
    }
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $data['profile_picture'] =
                $request->file('profile_picture')->store('profiles', 'public');

                }

/** @var \App\Models\User $user */

        $user->update($data);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profile updated successfully');
    }

    public function destroy()
    {
        $user = Auth::user();

/** @var \App\Models\User $user */
        $user->delete();

        return redirect('/')->with('success', 'Account deleted');
    }
}


