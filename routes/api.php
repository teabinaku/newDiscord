    <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;

    use Illuminate\Support\Facades\Log;

    Route::post('/add/user', [\App\Http\Controllers\SignUpController::class, 'index']);

    Route::post('/login', function (Request $request) {
        try {
            Log::info('Login attempt received'); // Log the request for debugging

            $credentials = [
                filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name' => $request->login,
                'password' => $request->password,
            ];

            // Attempt to authenticate using the credentials
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Create a token if the authentication is successful
                $token = $user->createToken('authToken')->plainTextToken;

                // Return success response
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token
                ], 200);
            }

            // Return failure response if credentials are incorrect
            return response()->json(['message' => 'Invalid credentials'], 401);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage()); // Log the error in the Laravel logs

            return response()->json([
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Protected route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

Route::middleware('auth:sanctum')->post('/add/friend/request', [\App\Http\Controllers\FriendRequestController::class,'addFriendRequest']);
    Route::get('/search/users', [\App\Http\Controllers\FriendController::class, 'searchUsers'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->post('/get/send/friend/requests', [\App\Http\Controllers\FriendRequestController::class,'sendFriendRequests']);
Route::middleware('auth:sanctum')->post('/received/send/friend/requests', [\App\Http\Controllers\FriendRequestController::class,'receivedFriendRequests']);


Route::middleware('auth:sanctum')->post('/approve/friend/requests', [\App\Http\Controllers\FriendRequestController::class, 'approveFriendRequest']);
Route::middleware('auth:sanctum')->post('/deny/friend/requests', [\App\Http\Controllers\FriendRequestController::class, 'denyFriendRequest']);


Route::middleware('auth:sanctum')->get('/friends', [\App\Http\Controllers\FriendController::class, 'getFriends']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = Auth::user();
    // Generate the full URL for the avatar if it exists
    $user->avatar = $user->avatar ? asset('storage/' . $user->avatar) : null;
    return response()->json($user);
    });

Route::middleware('auth:sanctum')->get('/mood/statuses', function (Request $request) {
$moodStatuses=\App\Models\MoodStatuses::all();
    return response()->json($moodStatuses);     });

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
        // Ensure the user is authenticated before calling tokens()->delete()
        if ($request->user()) {
            $request->user()->tokens()->delete();  // Invalidate the user's tokens
            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        return response()->json(['message' => 'No authenticated user found'], 401);  // Return an error if the user is not authenticated
    });
    Route::middleware('auth:sanctum')->post('/block-user', [\App\Http\Controllers\BlockedUserController::class, 'blockUser']);
    Route::middleware('auth:sanctum')->get('/blocked/users', [\App\Http\Controllers\BlockedUserController::class, 'getBlockedUsers']);
    Route::middleware('auth:sanctum')->post('/unblock/user', [\App\Http\Controllers\BlockedUserController::class, 'removeBlock']);
    Route::middleware('auth:sanctum')->post('/change/profile/picture', [\App\Http\Controllers\ProfileController::class, 'changeProfilePicture']);

    // Define the route in your API routes file (api.php)
    Route::middleware('auth:sanctum')->post('/conversation/send-message', [\App\Http\Controllers\MessageController::class, 'sendNewMessage']);


    Route::middleware('auth:sanctum')->post('/conversation/get-messages', [\App\Http\Controllers\MessageController::class, 'getMessagesWithFriend']);
    Route::middleware('auth:sanctum')->post('/message/react', [\App\Http\Controllers\MessageController::class, 'addReaction']);
    Route::middleware('auth:sanctum')->post('/unsend/message', [\App\Http\Controllers\MessageController::class, 'unsendMessage']);
    Route::middleware('auth:sanctum')->get('/notifications', [\App\Http\Controllers\NotificationController::class, 'getNotifications']);
    Route::middleware('auth:sanctum')->post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markNotificationAsRead']);


    Route::middleware('auth:sanctum')->post('/remove/friend', [\App\Http\Controllers\FriendController::class, 'removeFriend']);

Route::middleware('auth:sanctum')->post('/edit/profile', [\App\Http\Controllers\ProfileController::class, 'editProfileData']);


    Route::middleware('auth:sanctum')->post('/create/group-chat', [\App\Http\Controllers\GroupChatController::class, 'addGroupChat']);
