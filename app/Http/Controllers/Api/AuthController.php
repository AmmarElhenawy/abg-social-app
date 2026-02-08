<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function user(Request $request)
{

    try {
        return $this->success($request->user(), 'Success', 200);

    } catch (Throwable $e) {
        return $this->error('Unauthenticated', 401);
    }
}

}
