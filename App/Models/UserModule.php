<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModule extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_module_id';

    protected $fillable = [
        'user_id',
        'module_id',
    ];

    // Relationships

    // User.php
public function modules()
{
    return $this->belongsToMany(Module::class, 'user_modules', 'user_id', 'module_id')
                ->withTimestamps();
}

// Module.php
public function users()
{
    return $this->belongsToMany(User::class, 'user_modules', 'module_id', 'user_id')
                ->withTimestamps();
}
}