<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $primaryKey = 'module_id';

    protected $fillable = [
        'module_name',
        'description',
        'admin_id'
    ];

    // Relationships

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'module_id', 'module_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'module_id', 'module_id');
    }

    public function quizzes()
    {
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'module_id', 'module_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'module_id', 'module_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_modules', 'module_id', 'user_id')
                    ->withTimestamps();
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }
}

