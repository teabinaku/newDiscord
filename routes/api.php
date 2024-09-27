    <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    Route::post('/add/user', [\App\Http\Controllers\SignUpController::class, 'index']);

    Route::post('/login', function (Request $request) {
//        $request->validate([
//            'login' => 'required',
//            'password' => 'required',
//        ]);
        dump("erdh deri tek");

        $credentials = [
            filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name' => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Use createToken method after Sanctum is set up
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    });

    // Protected route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

Route::middleware('auth:sanctum')->post('/add/friend/request', [\App\Http\Controllers\FriendRequestController::class,'addFriendRequest']);


Route::middleware('auth:sanctum')->post('/get/send/friend/requests', [\App\Http\Controllers\FriendRequestController::class,'sendFriendRequests']);
Route::middleware('auth:sanctum')->post('/received/send/friend/requests', [\App\Http\Controllers\FriendRequestController::class,'receivedFriendRequests']);


Route::middleware('auth:sanctum')->post('/approve/friend/requests', [\App\Http\Controllers\FriendRequestController::class, 'approveFriendRequest']);
Route::middleware('auth:sanctum')->post('/deny/friend/requests', [\App\Http\Controllers\FriendRequestController::class, 'denyFriendRequest']);


Route::middleware('auth:sanctum')->get('/friends', [\App\Http\Controllers\FriendController::class, 'getFriends']);


