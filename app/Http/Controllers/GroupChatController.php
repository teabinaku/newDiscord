<?php

namespace App\Http\Controllers;

use App\Models\GroupChatMembers;
use App\Models\GroupChatsModel;
use App\Models\MessagesModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupChatController extends Controller
{
    public function addGroupChat(Request $request)
    {


        $newGroupChat = new GroupChatsModel();
        $newGroupChat->name = $request->group_chat_name;
        $newGroupChat->save();

        $PrimarygroupChatMember = new GroupChatMembers();
        $PrimarygroupChatMember->group_chat_id = $newGroupChat->id;
        $PrimarygroupChatMember->user_id = Auth::id();
        $PrimarygroupChatMember->save();


        foreach ($request->members as $memberId) {
            $groupChatMember = new GroupChatMembers();
            $groupChatMember->group_chat_id = $newGroupChat->id;
            $groupChatMember->user_id = $memberId;
            $groupChatMember->save();
        }

        return response()->json(['message' => 'Group chat created successfully'], 200);
    }

    public function getUserGroupChats()
    {
        $groupChats = Auth::user()->groupChats()->with('members')->get();

        return response()->json($groupChats, 200);
    }

    public function sendGroupChatMessage(Request $request){
        dump($request->all());
        $newMessage= new MessagesModel();
        $newMessage->sender_id=Auth::id();
        $newMessage->content=$request->content;
        $newMessage->group_chat_id=$request->group_chat_id;

        $newMessage->save();

        return response()->json(['message' => 'Message send successfully'], 200);

    }

    public function getGroupChatMessages(Request $request)
    {
        $allMessages=MessagesModel::where('group_chat_id',$request->group_chat_id)
            ->with('groupChat')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($allMessages, 200);
    }


}
