<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $primaryKey = 'module_id';

    protected $fillable = [
        'name',
        'description',
    ];

    // Relationships

    public function videos()
    {
        return $this->hasMany(Video::class, 'module_id', 'module_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'module_id', 'module_id');
    }

    public function quizzes()
    {
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'module_id', 'module_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'module_id', 'module_id');
    }

    public function userModules()
    {
        return $this->hasMany(UserModule::class, 'module_id', 'module_id');
    }
}

