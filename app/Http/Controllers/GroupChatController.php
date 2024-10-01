<?php

namespace App\Http\Controllers;

use App\Models\GroupChatMembers;
use App\Models\GroupChatsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupChatController extends Controller
{
    public function addGroupChat(Request $request)
    {
//dump($request->all());

        $newGroupChat = new GroupChatsModel();
        $newGroupChat->name = $request->groupName;
        $newGroupChat->save();

//        $groupChatMember = new GroupChatMembers();
//        $groupChatMember->group_chat_id = $newGroupChat->id;
//        $groupChatMember->user_id = Auth::id();
//        $groupChatMember->save();

        foreach ($request->members as $memberId) {
            $groupChatMember = new GroupChatMembers();
            $groupChatMember->group_chat_id = $newGroupChat->id;
            $groupChatMember->user_id = $memberId;
            $groupChatMember->save();
        }

        return response()->json(['message' => 'Group chat created successfully'], 200);
    }

}
