<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $primaryKey = 'progress_id';
    
    protected $fillable = [
        'user_id',
        'module_id',
        'grade',
        'is_completed',
        'completed_at',
        'correct_answers',
        'completion_percentage'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'is_completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function score()
    {
        return $this->hasOne(Score::class, 'module_id', 'module_id')
                   ->where('user_id', $this->user_id);
    }
}