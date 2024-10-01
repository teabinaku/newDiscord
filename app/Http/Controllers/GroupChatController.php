<?php

namespace App\Http\Controllers;

use App\Models\GroupChatsModel;
use Illuminate\Http\Request;

class GroupChatController extends Controller
{
    public function addGroupChat(Request $request)
    {
        $newGroupChat = new GroupChatsModel();
        $newGroupChat->name=$request->group_chat_name;
        $newGroupChat->save();
        foreach($request->memeber_id as $member){

            $groupChatMembers= new GroupChatsModel();
            $groupChatMembers->group_chat_id=$newGroupChat->id;
            $groupChatMembers->user_id=$member->id;
            $groupChatMembers->save();
        }




    }
}
