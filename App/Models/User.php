<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;  // Added for API token support

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  // Added HasApiTokens

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
    
}