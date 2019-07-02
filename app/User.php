<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function chats(){        
        return $this->belongsToMany('App\Chat', 'permissions')
        ->withPivot('permissions');
    }

    public function messages(){
        return $this->hasMany('App\Message');
    }

    public function contacts(){        
        return $this->belongsToMany('App\User', 'contacts', 'user_id', 'contact_id');
    }
}
