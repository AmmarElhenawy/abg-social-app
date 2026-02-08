<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
    {
        $user=Auth::user();
        $userId=$user->id;

        /** @var \App\Models\User $user */
        $friends = $user->friends()
                                ->withCount('friends')
                                ->paginate(20);

        return view('friends.index', compact('friends'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Friend $friend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Friend $friend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Friend $friend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Friend $friend)
    {
        //
    }

    public function send(User $user)
    {

        $userAuth=Auth::user();
        $userId=$userAuth->id;

    if ($user->id === $userId) {
            return redirect()->back()->with('error', 'You cannot send friend request to yourself!');
        }



        $existingFriendship = Friend::where(function($q) use ($user) {
        $userAuth=Auth::user();
        $userId=$userAuth->id;
            $q->where('user_id', $userId)
            ->where('friend_id', $user->id);
        })->orWhere(function($q) use ($user, $userId) {
            $q->where('user_id', $user->id)
            ->where('friend_id', $userId);
        })->first();

        if ($existingFriendship) {
            if ($existingFriendship->status === 'accepted') {
                return redirect()->back()->with('error', 'You are already friends!');
            }
            if ($existingFriendship->status === 'pending') {
                return redirect()->back()->with('error', 'Friend request already sent!');
            }
        }

        Friend::create([
            'user_id' => $userId,
            'friend_id' => $user->id,
            'status' => 'pending',
        ]);


        return redirect()->back()->with('success', 'Friend request sent!');
    }


    public function accept(User $user)
    {
        $userAuth=Auth::user();
        $userId=$userAuth->id;

        $friendship = Friend::where('user_id', $user->id)
                                ->where('friend_id', $userId)
                                ->where('status', 'pending')
                                ->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Friend request not found!');
        }

        $friendship->status = 'accepted';
        $friendship->save();

        // يمكن إرسال Notification هنا
        // $user->notify(new FriendRequestAcceptedNotification(auth()->user()));

        return redirect()->back()->with('success', 'Friend request accepted!');
    }

    /**
     * رفض طلب صداقة
     */
    public function reject(User $user)
    {
        $userAuth=Auth::user();
        $userId=$userAuth->id;

        $friendship = Friend::where('user_id', $user->id)
                                ->where('friend_id', $userId)
                                ->where('status', 'pending')
                                ->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Friend request not found!');
        }

        $friendship->delete();

        return redirect()->back()->with('success', 'Friend request rejected!');
    }

    /**
     * إلغاء طلب صداقة مُرسل
     */
    public function cancel(User $user)
    {
        $userAuth=Auth::user();
        $userId=$userAuth->id;
        $friendship = Friend::where('user_id', $userId)
                                ->where('friend_id', $user->id)
                                ->where('status', 'pending')
                                ->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Friend request not found!');
        }

        $friendship->delete();

        return redirect()->back()->with('success', 'Friend request cancelled!');
    }

    /**
     * إنهاء صداقة (Unfriend)
     */
    public function unfriend(User $user)
    {


        $friendship = Friend::where(function($q) use ($user) {
        $userAuth=Auth::user();
        $userId=$userAuth->id;
            $q->where('user_id', $userId)
            ->where('friend_id', $user->id);
        })->orWhere(function($q) use ($user) {

        $userAuth=Auth::user();
        $userId=$userAuth->id;

        $q->where('user_id', $user->id)
            ->where('friend_id', $userId);
        })->where('status', 'accepted')
        ->first();

        if (!$friendship) {
            return redirect()->back()->with('error', 'Friendship not found!');
        }

        $friendship->delete();

        return redirect()->back()->with('success', 'Friendship ended!');
    }

    /**
     * عرض طلبات الصداقة
     */
    public function requests()
    {
        $userAuth=Auth::user();
        $userId=$userAuth->id;
        $receivedRequests = Friend::where('friend_id', $userId)
                                    ->where('status', 'pending')
                                    ->with('sender')
                                    ->latest()
                                    ->get();

        $sentRequests = Friend::where('user_id', $userId)
                                ->where('status', 'pending')
                                ->with('receiver')
                                ->latest()
                                ->get();

        return view('friends.requests', compact('receivedRequests', 'sentRequests'));
    }



}
