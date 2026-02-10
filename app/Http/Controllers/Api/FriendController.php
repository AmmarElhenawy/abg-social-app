<?php
namespace App\Http\Controllers\Api;

use App\Events\FriendRequestSent;
use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FriendController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $user = Auth::user();
            if (!$user) {
                    return $this->error('Unauthorized', 401);
                }
            $userId=$user->id;
            $friends = Friend::where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                ->orWhere('receiver_id',$userId );
            })->where('status', 'accepted')->get();
            return $this->success($friends);
        } catch (Exception $e) {
            return $this->error('Failed to fetch friends', 500);
        }
    }
    public function sendRequest(User $receiver)
    {
        // try {
        $sender = Auth::user();
        if (!$sender) {
            return $this->error('Unauthenticated', 401);
        }

            if ($sender->id === $receiver->id) {
            return $this->error('You cannot send a friend request to yourself', 400);
        }

        $exists = Friend::where('sender_id', $sender->id)
                        ->where('receiver_id', $receiver->id)
                        ->exists();
        if ($exists) {
            return $this->error('Friend request already sent', 409);
        }

            Friend::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
        ]);

            broadcast(new FriendRequestSent($receiver))->toOthers();
            return $this->success(null, 'Friend request sent');
        // } catch (Exception $e) {
        //     return $this->error('Failed to send friend request', 500);
        // }
        }
    public function acceptRequest(User $sender)
    {
        try {
            $receiver = Auth::user();
            if (!$receiver) {
                    return $this->error('Unauthorized', 401);
                }

        $friend = Friend::where('sender_id', $sender->id)
                        ->where('receiver_id', $receiver->id)
                        ->first();

        if (!$friend) {
            return $this->error('Friend request not found', 404);
        }

        // $friend->update(['status' => 'accepted']);
            $friend->status = 'accepted';
    $friend->save();
            return $this->success(null, message: 'Friend request accepted');
        } catch (Exception $e) {
            return $this->error('Failed to accept friend request', 500);
        }
    }


    //check
    public function rejectRequest(User $sender)
    {
        try {
            $receiver = Auth::user();
            if (!$receiver) {
                return $this->error('Unauthorized', 401);
            }

            $friend = Friend::where('sender_id', $sender->id)
                ->where('receiver_id', $receiver->id)
                ->first();

            if (!$friend) {
                return $this->error('Friend request not found', 404);
            }

            $friend->update(['status' => 'rejected']);
        return $this->success(null, 'Friend request rejected');
    } catch (Exception $e) {
            return $this->error('Failed to reject friend request', 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(User $receiver)
{
    try {
        $authUser = Auth::user();
        if (!$authUser) {
            return $this->error('Unauthorized', 401);
        }

        $deleted = Friend::where(function ($q) use ($authUser, $receiver) {
            $q->where('sender_id', $authUser->id)
            ->where('receiver_id', $receiver->id);
        })
        ->orWhere(function ($q) use ($authUser, $receiver) {
            $q->where('sender_id', $receiver->id)
            ->where('receiver_id', $authUser->id);
        })
        ->delete();

        if (!$deleted) {
            return $this->error('Friend not found', 404);
        }

        return $this->success(null, 'Unfriended');

    } catch (Exception $e) {
        return $this->error('Failed to unfriend', 500);
    }
}

}

