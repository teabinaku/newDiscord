<?php

namespace App\Http\Controllers;

use App\Models\BlockedUsersModel;
use App\Models\FriendsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function getFriends(){
        $friends = FriendsModel::where(function($query) {
            $query->where('user_id_1', Auth::id())
                ->orWhere('user_id_2', Auth::id());
        })
            ->with(['user1' => function($query) {
                $query->where('id', '!=', Auth::id());  // Exclude Auth::id() from user1
            }, 'user2' => function($query) {
                $query->where('id', '!=', Auth::id());  // Exclude Auth::id() from user2
            }])
            ->get();


        return response()->json($friends);
    }

    public function searchUsers(Request $request)
    {
        $searchQuery = $request->input('query'); // Fetch the search query

        // Validate the search input
        $request->validate([
            'query' => 'required|string|min:1',
        ]);


        $users = User::where('name', 'like', '%' . $searchQuery . '%')
            ->orWhere('email', 'like', '%' . $searchQuery . '%')
            ->where('id', '!=', Auth::id())
            ->get();

        return response()->json($users); // Return the result as a JSON response
    }


    public function removeFriend(Request $request){
        $friend=FriendsModel::find($request->id);

        $friend->delete();

        return response()->json('Friend removed',200);
    }






}
