<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
use Notifiable, HasFactory;    

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'password',
        'date_of_birth',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationships
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id', 'user_id');
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class, 'user_id', 'user_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'user_id', 'user_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'user_id', 'user_id');
    }

    public function userModules()
    {
        return $this->hasMany(UserModule::class, 'user_id', 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
}