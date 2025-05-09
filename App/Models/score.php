<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $primaryKey = 'score_id';

    protected $fillable = [
        'user_id',
        'module_id',
        'quiz_id',
        'level_id',
        'grade',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }
}