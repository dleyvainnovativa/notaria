<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'memorial_id',
        'uuid',
        'status',
    ];

    /**
     * Get the memorial that this QR code belongs to.
     */
    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the visits for this QR code.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}
