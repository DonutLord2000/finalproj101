<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_anonymous',
    ];

    // Keep these for consistent property access in Blade
    protected $appends = ['upvotes', 'hearts', 'comments_count'];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    // Accessor for upvotes count
    public function getUpvotesAttribute(): int
    {
        // Prioritize the count loaded by withCount/loadCount if available
        return $this->attributes['upvotes_count'] ?? $this->reactions()->where('type', 'upvote')->count();
    }

    // Accessor for hearts count
    public function getHeartsAttribute(): int
    {
        // Prioritize the count loaded by withCount/loadCount if available
        return $this->attributes['hearts_count'] ?? $this->reactions()->where('type', 'heart')->count();
    }

    // Accessor for comments count
    public function getCommentsCountAttribute(): int
    {
        // Prioritize the count loaded by withCount/loadCount if available
        return $this->attributes['comments_count'] ?? $this->comments()->count();
    }

    /**
     * Get the display name for the thread's author.
     */
    public function getUserDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Anonymous User' : ($this->user->name ?? 'Unknown User');
    }

    /**
     * Get the display profile picture URL for the thread's author.
     */
    public function getDisplayProfilePictureUrlAttribute(): string
    {
        // Use a generic placeholder for anonymous users
        return $this->is_anonymous ? '/placeholder.svg?height=48&width=48' : ($this->user->profile?->profile_picture_url ?? $this->user->profile_photo_url);
    }

    /**
     * Get the display role for the thread's author.
     */
    public function getDisplayRoleAttribute(): string
    {
        return $this->is_anonymous ? 'guest' : ($this->user->role ?? 'unknown');
    }
}
