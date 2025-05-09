<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $primaryKey = 'level_id';

    protected $fillable = [
        'name',
        'advancement',
    ];
    
    // Relationships

    public function scores()
    {
        return $this->hasMany(Score::class, 'level_id', 'level_id');
    }
}