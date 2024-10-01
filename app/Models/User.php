<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userType()
    {
        return $this->belongsTo(UserTypeModel::class, 'user_type_id');
    }

    // User belongs to one MoodStatus
    public function moodStatus()
    {
        return $this->belongsTo(MoodStatuses::class, 'mood_status_id');
    }
    public function getAvatarAttribute($value)
    {
        // Check if the user has an avatar
        if ($value) {
            // Return the full path to the avatar if it exists
            return asset('storage/' . $value);
        }

        // Return a default avatar if the user does not have an avatar
        return asset('pic.jpg'); // Ensure you have a default avatar in storage
    }

    public function groupChatMemberships()
    {
        return $this->hasMany(GroupChatMembers::class, 'user_id');
    }

    public function groupChats()
    {
        return $this->belongsToMany(GroupChatsModel::class, 'group_chat_members', 'user_id', 'group_chat_id');
    }
}
