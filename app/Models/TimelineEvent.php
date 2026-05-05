<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;


class TimelineEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'memorial_id',
        'event_date',
        'title',
        'description',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    protected $appends = ['date'];

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->event_date?->format('Y-m-d'),
        );
    }

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }
}
