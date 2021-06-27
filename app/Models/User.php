<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Role;
use App\Models\SocialAccount;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Thomaswelton\LaravelGravatar\Facades\Gravatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\Password\CustomResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['is_admin', 'profile_pic'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function posts(){
        return $this->hasMany(Post::class, 'user_id');
    }

    public function getProfilePicAttribute(){
        return Gravatar::src($this->email, 60);
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getIsAdminAttribute() : bool
    {
        // return $this->role? $this->role->name == "admin": false;
        return $this->role->name == "admin";
    }

    public function socialAccounts(){
        return $this->hasMany(SocialAccount::class, 'user_id');
    }
}
