<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends BaseApiController
{
/**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Get list of users",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="List of users",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(ref="#/components/schemas/User")
 *             )
 *         )
 *     )
 * )
 */
    public function index()
    {
        try {
            $users = User::paginate(20);
            return $this->success($users, 'Users fetched successfully', 200);
        } catch (Exception $e) {
            return $this->error('Failed to fetch users', 500);
        }
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
    public function show(User $user)
{
    try {
        $user->loadCount(['posts', 'friends']);
        return $this->success($user, 'success', 200);
    } catch (ModelNotFoundException $e) {
        return $this->error('User not found with this ID', 404);
    }
}

    /**
     * Update the specified resource in storage.
     */
    //check
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user ) {
                return $this->error('Unauthorized', 401);
            }
    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'bio' => 'sometimes|nullable|string|max:500',
        'profile_picture' => 'sometimes|nullable|image|max:2048',
    ]);

    if ($request->hasFile('profile_picture')) {
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $validated['profile_picture'] = $path;
    }
    /** @var \App\Models\User $user */
    $user->update($validated);

    return $this->success( $user ,'Profile updated successfully', 200);
    //code...
        }catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error($e->errors(), 422);
        } catch (Exception $e) {
            return $this->error('Failed to update profile', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
        {
        try {
            $user = Auth::user();
            if (!$user || $user->id != $id) {
                return $this->error('Unauthorized', 401);
            }

            /** @var \App\Models\User $user */
            $user->delete();

            return $this->success(null, 'Profile deleted successfully', 200);

        } catch (ModelNotFoundException $e) {
            return $this->error('User not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to delete profile', 500);
        }
    }

        public function friends(User $user)
    {
$friends = $user->friends()->paginate(20);


            return $this->success($friends->items(), 'success', 200);


    }
    public function posts(User $user)
    {
try {
    $posts = $user->posts()->with(['user', 'likes','comments'])->withCount(['likes','comments'])->latest()->paginate(10);
    return $this->success($posts->items(), 'success', 200);
        } catch (Exception $e) {
            return $this->error('Failed to fetch posts', 500);
        }
    }

    public function uploadProfileImage(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return $this->error('Unauthorized', 401);
    }

    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($user->image) {
        Storage::disk('public')->delete($user->image);
    }

    $path = $request->file('image')->store('profiles', 'public');

    /** @var \App\Models\User $user */
    $user->update([
        'profile_picture' => $path,
    ]);

    return $this->success([
        'image_url' => asset('storage/' . $path)
    ], 'Profile image updated');
}

public function search(Request $request)
{
    $request->validate([
        'q' => 'required|string|min:1'
    ]);

    $users = User::where('name', 'LIKE', '%' . $request->q . '%')
                ->select('id', 'name', 'profile_picture')
                // ->where('id', '!=', auth()->id());
                ->paginate(20);

    return $this->success($users->items(), 'Users fetched');
}

}
