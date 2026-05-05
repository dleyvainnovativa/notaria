<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemorialParagraph extends Model
{
    protected $fillable = [
        'memorial_id',
        'content',
        'position',
    ];

    public function memorial()
    {
        return $this->belongsTo(Memorial::class);
    }
}
