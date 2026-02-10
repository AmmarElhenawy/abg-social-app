<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'image',
        'isAcive',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    // public function profile()
    // {
    //     return $this->hasOne(Profile::class);
    // }
    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    // app/Models/User.php

// public function friends()
// {
//     return $this->belongsToMany(
//         User::class,
//         'friends',
//         'user_id',
//         'friend_id'
//     );
// }

public function friends()
{
    return $this->hasMany(Friend::class, 'sender_id')
                ->where('status', 'accepted');
}

public function allFriends()
{
    return Friend::where(function ($q) {
        $q->where('sender_id', $this->id)
        ->orWhere('receiver_id', $this->id);
    })->where('status', 'accepted');
}

public function friendsOfFriend()
{
    return $this->hasMany(Friend::class, 'sender_id')->where('status', 'accepted');
}

    public function confirmedFriends()
    {
        // هتجيب كل الأصدقاء سواء sender أو receiver مع status = accepted
        return $this->hasMany(Friend::class, 'sender_id')
                    ->where('status', 'accepted')
                    ->orWhere('receiver_id', $this->id);
    }

}
