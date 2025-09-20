<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->has('is_admin') ? (bool) $request->is_admin : false,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    /**
     * User login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate a new token
        $token = bin2hex(random_bytes(40));

        // Save the token to the database
        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Logged in successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * User logout
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->remember_token = null;
        $user->save();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }


    /**
     * Get authenticated user data
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }


    /**
     * Admin login
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->getAuthPassword()) || !$user->is_admin) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect or you are not an admin.'],
            ]);
        }

        // Generate token
        $token = bin2hex(random_bytes(40));

        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Admin logged in successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

}
