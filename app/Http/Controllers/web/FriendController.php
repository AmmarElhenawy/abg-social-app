<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

$friends = Friend::where(function ($q) use ($userId) {
        $q->where('sender_id', $userId)
        ->orWhere('receiver_id', $userId);
    })
    ->where('status', 'accepted')
    ->with(['user', 'friend']) // ← هنا استخدم العلاقات الموجودة في الموديل
    ->get();


        return view('friends.index', compact('friends'));
    }

    public function requests()
    {
        $requests = Friend::where('receiver_id', Auth::id())
    ->where('status', 'pending')
    ->with('user') 
    ->get();


        return view('friends.requests', compact('requests'));
    }

    public function sendRequest(User $receiver)
    {
        $sender = Auth::user();

        if ($sender->id === $receiver->id) {
            return back()->withErrors('You cannot add yourself');
        }

        $exists = Friend::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->exists();

        if ($exists) {
            return back()->withErrors('Request already sent');
        }

        Friend::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
        ]);

        return back()->with('success', 'Friend request sent');
    }

    public function acceptRequest(User $sender)
    {
        Friend::where('sender_id', $sender->id)
            ->where('receiver_id', Auth::id())
            ->update(['status' => 'accepted']);

        return back()->with('success', 'Friend request accepted');
    }

    public function rejectRequest(User $sender)
    {
        Friend::where('sender_id', $sender->id)
            ->where('receiver_id', Auth::id())
            ->update(['status' => 'rejected']);

        return back()->with('success', 'Friend request rejected');
    }

    public function destroy(User $user)
    {
        Friend::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())
              ->where('receiver_id', $user->id);
        })
        ->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', Auth::id());
        })
        ->delete();

        return back()->with('success', 'Unfriended');
    }
}
