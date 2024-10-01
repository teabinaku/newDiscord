<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GroupChatsModel extends Model
{
    protected $table = 'group_chats';

    protected $fillable = ['name'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_chat_members', 'group_chat_id', 'user_id');
    }
}
