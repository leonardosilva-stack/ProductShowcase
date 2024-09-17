<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();


            if ($user instanceof \App\Models\User) {
                $token = $user->createToken($request->device_name ?? 'Personal Access Token')->plainTextToken;

                return response()->json(['token' => $token]);
            }

            return response()->json(['error' => 'Unable to create token, user not found.'], 500);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        if ($user = $request->user()) {
            $user->tokens()->delete();

            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['error' => 'No user to log out'], 401);
    }
}
