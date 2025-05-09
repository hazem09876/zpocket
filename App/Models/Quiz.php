<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $primaryKey = 'quiz_id';

    protected $fillable = [
        'module_id',
        'content',
        'theme',
    ];

    // Relationships

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'quiz_id', 'quiz_id');
    }
}