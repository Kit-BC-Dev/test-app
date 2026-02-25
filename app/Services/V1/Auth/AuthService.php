<?php

namespace App\Services\V1\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
class AuthService
{
    public function login(array $credentials): JsonResponse
    {
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.',
            ], 422);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Logged in successfully.',
            'user' => $user,
        ], 200);
    }
}