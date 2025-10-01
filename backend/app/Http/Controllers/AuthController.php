<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // validate role as employee,admin,user
            'role' => 'nullable|in:employee,admin,user',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:20|confirmed'
        ]);
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:20'
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('MuleToken')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }
public function logout(Request $request)
{
    $user = $request->user();
if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Not authenticated'
        ], 401);
    }


    $user->currentAccessToken()->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Logged out successfully'
    ], 200);
}
}
