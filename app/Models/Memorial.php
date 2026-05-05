<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Memorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'deceased_name',
        'birth_date',
        'death_date',
        'biography',
        'playlist',
        'profile_image_url',
        'cover_image_url',
        'is_public',
        'access_password',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'is_public' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }


    /**
     * Get the user (client) that owns the memorial.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the QR code associated with the memorial.
     */
    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class);
    }

    /**
     * Get the media items for the memorial.
     */
    public function mediaItems(): HasMany
    {
        return $this->hasMany(MediaItem::class);
    }

    /**
     * Get the tributes for the memorial.
     */
    public function tributes(): HasMany
    {
        return $this->hasMany(Tribute::class);
    }

    /**
     * Get the timeline events for the memorial.
     */
    public function timelineEvents(): HasMany
    {
        return $this->hasMany(TimelineEvent::class)
            ->orderBy('event_date', 'asc'); // or 'desc'
    }

    /**
     * Get the payment for the memorial.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
    public function paragraphs()
    {
        return $this->hasMany(MemorialParagraph::class)
            ->orderBy('position');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(MemorialInvitation::class);
    }

    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memorial_invitations')
            ->withPivot(['role', 'accepted_at'])
            ->whereNotNull('accepted_at');
    }

    public function canAccess(User $user): bool
    {
        // 👑 Owner
        if ($this->user_id === $user->id) {
            return true;
        }

        return $this->invitations()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('email', $user->email); // 🔥 fallback
            })
            // ->whereNotNull('accepted_at')
            // ->where(function ($query) {
            //     $query->whereNull('expires_at')
            //         ->orWhere('expires_at', '>', now());
            // })
            ->exists();
    }

    public function canEditModule(User $user, string $module): bool
    {
        if ($this->user_id === $user->id) {
            return true;
        }

        $invitation = $this->invitations()
            ->where('user_id', $user->id)
            // ->whereNotNull('accepted_at')
            ->first();

        if (! $invitation) {
            return false;
        }

        return match ($module) {
            'info' => $invitation->can_edit_info,
            'timeline' => $invitation->can_edit_timeline,
            'life' => $invitation->can_edit_life,
            'gallery' => $invitation->can_edit_gallery,
            'messages' => $invitation->can_edit_messages,
            default => false,
        };
    }
}
