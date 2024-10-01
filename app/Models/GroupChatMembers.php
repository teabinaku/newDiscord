<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GroupChatMembers extends Model
{
    protected $table = 'group_chat_members';

    protected $fillable = ['user_id','group_chat_id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function groupChat()
    {
        return $this->belongsTo(GroupChatsModel::class, 'group_chat_id');
    }
}
