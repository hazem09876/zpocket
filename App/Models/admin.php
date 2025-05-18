<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    use HasFactory;

    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'user_id',
        'module_id',
        'permission', 
    ];

    // Set default attribute values
    protected $attributes = [
        'permission' => 'full', 
    ];

    // Relationships
    public function user(): BelongsTo
    {
          return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class, 'module_id', 'module_id');
    }
}