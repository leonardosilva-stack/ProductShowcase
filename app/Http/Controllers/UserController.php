<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        try {
            $user = User::first(); // Assuming there's only one user
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(UserRequest $request)
    {
        try {
            $user = User::first(); // Assuming there's only one user

            $validatedData = $request->validated();

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->status = $validatedData['status'];

            if ($request->filled('password')) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->created_at = $validatedData['created_at'];
            $user->updated_at = $validatedData['updated_at'];

            $user->save();

            return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the user', 'message' => $e->getMessage()], 500);
        }
    }
}
