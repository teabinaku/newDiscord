<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Login Method
    public function logUser(Request $request)
    {
        // Validate input
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        // Try to find the user by email or username
        $credentials = [
            filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name' => $request->input('login'),
            'password' => $request->input('password'),
        ];

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Authentication passed
            $user = Auth::user(); // Get authenticated user

            // Generate tokens
            $token = $user->createToken('authToken')->plainTextToken;
            $refreshToken = Str::random(64); // Generate refresh token

            // Save refresh token in the database
            $user->tokens()->update([
                'refresh_token' => $refreshToken
            ]);

            // Return success and user data, including access and refresh tokens
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
                'refresh_token' => $refreshToken
            ], 200);
        }

        // Authentication failed
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Signup Method
    public function index(Request $request)
    {
        // Create new user
        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->user_type_id = 1;
        $newUser->mood_status_id = 1;

        if ($newUser->save()) {
            // Log the user in automatically after successful signup
            Auth::login($newUser);

            // Generate tokens
            $token = $newUser->createToken('authToken')->plainTextToken;
            $refreshToken = Str::random(64); // Generate refresh token

            // Save refresh token in the database
            $newUser->tokens()->update([
                'refresh_token' => $refreshToken
            ]);

            // Return a success response with the token and refresh token
            return response()->json([
                'message' => 'Sign Up Successful',
                'token' => $token,
                'refresh_token' => $refreshToken
            ], 200);
        } else {
            return response()->json(['message' => 'Sign Up Failed'], 500);
        }
    }

    // Refresh Token Method
    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required'
        ]);

        $token = \DB::table('personal_access_tokens')
            ->where('refresh_token', $request->refresh_token)
            ->first();

        if (!$token) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        // Generate a new access token
        $user = User::find($token->tokenable_id);
        $newToken = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $newToken
        ]);
    }
}
