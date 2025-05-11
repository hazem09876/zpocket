<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $primaryKey = 'video_id';

    protected $fillable = [
        'module_id',
        'embed_code',
        'title',
    ];

    // Relationships

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }
}