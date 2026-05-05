<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'memorial_id',
        'author_name',
        'message',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }
}
