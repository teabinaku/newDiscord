<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    public function index(Request $request)
    {

        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->user_type_id = 1;
        $newUser->mood_status_id = 1;

        if ($newUser->save()) {
            return response()->json(['message' => 'Sign Up Successful'], 200);
        } else {
            return response()->json(['message' => 'Sign Up Failed'], 500);
        }
    }
//    public function index(Request $request)
//    {
//        // Create new user
//        $newUser = new User();
//        $newUser->name = $request->name;
//        $newUser->email = $request->email;
//        $newUser->password = Hash::make($request->password);
//        $newUser->user_type_id = 1;
//        $newUser->mood_status_id = 1;
//
//        if ($newUser->save()) {
//            // Log the user in automatically after successful signup
//            Auth::login($newUser);
//
//            // Generate token for the new user
//            $token = $newUser->createToken('authToken')->plainTextToken;
//
//            // Return a success response with the token
//            return response()->json([
//                'message' => 'Sign Up Successful',
//                'token' => $token
//            ], 200);
//        } else {
//            return response()->json(['message' => 'Sign Up Failed'], 500);
//        }
//    }

}
