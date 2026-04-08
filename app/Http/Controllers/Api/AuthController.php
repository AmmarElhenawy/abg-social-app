<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;
use Throwable;

class AuthController extends BaseApiController
{
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'isAcive' => true,
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'token' => $token
            ], 'User registered',201);

        } catch (ValidationException $e) {
            return $this->error(`Validation error `,433,$e->errors());


        } catch (Throwable $e) {
            return $this->error(`Something went wrong`,500);

        }
        }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $data['email'])->first();

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                return $this->error('Invalid credentials', 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'token' => $token
            ], 'Logged in',200);

        } catch (ValidationException $e) {
        return $this->error(
            'Validation error',
            422,
            $e->errors()
        );

    }
    catch (Throwable $e) {
        return $this->error('Something went wrong', 500,$e);
    }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->success(null, 'Logged out',200);
        } catch (Throwable $th) {
                    return $this->error('Unauthenticated', 401,$th);

        }
    }

    public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $otp = rand(100000, 999999);

    $user->update([
        'verification_code' => $otp,
        'verification_code_expires_at' => now()->addMinutes(5),
    ]);

        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Reset Password OTP');
    });

    return response()->json([
        'message' => 'OTP sent to your email'
    ]);
}

public function verifyResetPassword(Request $request)
{
    $request->validate([
        'email' => 'required',
        'otp' => 'required'
    ]);

    $user = User::where('email', $request->phone)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    if (
        $user->verification_code !== $request->otp ||
        now()->greaterThan($user->verification_code_expires_at)
    ) {
        return response()->json(['message' => 'Invalid or expired OTP'], 400);
    }

        $user->update([
        'verification_code' => null,
        'verification_code_expires_at' => null,
        'is_reset_verified' => true
    ]);

    return response()->json([
        'message' => 'OTP verified'
    ]);
}

public function changePassword(Request $request)
{
    $request->validate([
        'email' => 'required',
        'password' => 'required|min:6'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // if (!$user->verification_code) {
    //     return response()->json([
    //         'message' => 'You must verify OTP first'
    //     ], 401);
    // }
        if (!$user->is_reset_verified) {
        return response()->json([
            'message' => 'You must verify OTP first'
        ], 401);
    }

    $user->update([
        'password' => bcrypt($request->password),
        'is_reset_verified' => false
    ]);

    return response()->json([
        'message' => 'Password changed successfully'
    ]);
}




    public function user(Request $request)
{

    try {
        return $this->success($request->user(), 'Success', 200);

    } catch (Throwable $e) {
        return $this->error('Unauthenticated', 401);
    }
}

}
