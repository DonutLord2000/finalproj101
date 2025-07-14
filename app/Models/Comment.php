<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'thread_id',
        'content',
        'edited_at',
        'edited_by',
        'deleted_at',
        'deleted_by',
        'is_anonymous', // Add this line
    ];

    protected $casts = [
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_anonymous' => 'boolean', // Cast to boolean
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    // Relationship for the user who edited the comment
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    // Relationship for the user who deleted the comment
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function isEdited(): bool
    {
        return !is_null($this->edited_at);
    }

    public function isDeleted(): bool
    {
        return !is_null($this->deleted_at);
    }

    /**
     * Get the display name for the comment's author.
     */
    public function getUserDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Anonymous User' : ($this->user->name ?? 'Unknown User');
    }

    /**
     * Get the display profile picture URL for the comment's author.
     */
    public function getDisplayProfilePictureUrlAttribute(): string
    {
        // Use a generic placeholder for anonymous users
        return $this->is_anonymous ? '/placeholder.svg?height=32&width=32' : ($this->user->profile?->profile_picture_url ?? $this->user->profile_photo_url);
    }

    /**
     * Get the display role for the comment's author.
     */
    public function getDisplayRoleAttribute(): string
    {
        return $this->is_anonymous ? 'guest' : ($this->user->role ?? 'unknown');
    }
}
