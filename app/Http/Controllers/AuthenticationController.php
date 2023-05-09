<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            return response()->json([
                'token' => $user->createToken('blog')->plainTextToken,
                'user' => $user
            ]);
        }

        return response()->json([
            'status' => 'error',
        ]);
    }

    /**
     * Handle logout attempt.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout',
        ]);
    }
}
