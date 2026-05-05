<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemorialInvitation extends Model
{
    protected $fillable = [
        'memorial_id',
        'invited_by',
        'email',
        'user_id',
        'token',
        'accepted_at',
        'expires_at',

        // permissions
        'can_edit_info',
        'can_edit_timeline',
        'can_edit_life',
        'can_edit_gallery',
        'can_edit_messages',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $appends = [
        'permissions',
        'permissions_count',
        'name',
    ];

    public function getPermissionsAttribute()
    {
        return [
            'info' => $this->can_edit_info,
            'timeline' => $this->can_edit_timeline,
            'life' => $this->can_edit_life,
            'gallery' => $this->can_edit_gallery,
            'messages' => $this->can_edit_messages,
        ];
    }
    public function getNameAttribute()
    {
        return $this->user?->name ?? 'Invitado';
    }
    public function getPermissionsCountAttribute()
    {
        return collect([
            $this->can_edit_info,
            $this->can_edit_timeline,
            $this->can_edit_life,
            $this->can_edit_gallery,
            $this->can_edit_messages,
        ])->filter()->count();
    }

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
