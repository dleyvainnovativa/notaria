<?php

namespace App\Models;

// Make sure these are imported
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'firebase_uid',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // It's good practice to hide the Firebase UID from API responses
        'firebase_uid',
    ];

    // --- ADD THESE TWO METHODS ---

    /**
     * Get the memorials owned by the user.
     * This defines the one-to-many relationship.
     */
    public function memorials(): HasMany
    {
        return $this->hasMany(Memorial::class);
    }

    /**
     * Get the payments made by the user.
     * This defines the one-to-many relationship.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function sharedMemorials(): BelongsToMany
    {
        return $this->belongsToMany(Memorial::class, 'memorial_invitations')
            ->withPivot(['role', 'accepted_at'])
            ->whereNotNull('accepted_at');
    }
}
