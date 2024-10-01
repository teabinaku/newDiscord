<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
//    public function index(Request $request)
//    {
//
//        $newUser = new User();
//        $newUser->name = $request->name;
//        $newUser->email = $request->email;
//        $newUser->password = Hash::make($request->password);
//        $newUser->user_type_id = 1;
//        $newUser->mood_status_id = 1;
//
//        if ($newUser->save()) {
//            return response()->json(['message' => 'Sign Up Successful'], 200);
//        } else {
//            return response()->json(['message' => 'Sign Up Failed'], 500);
//        }
//    }
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


}
