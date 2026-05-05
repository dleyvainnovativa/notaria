<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'memorial_id',
        'type',
        'url',
        'thumbnail_url',
        'caption',
        'position',
    ];

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }
    protected static function booted()
    {
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('position');
        });
    }
}
