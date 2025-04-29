<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'profile_picture',
        'cover_picture',
        'address',
        'contact_number',
        'bio',
        'is_verified'
    ];

    protected $appends = ['profile_picture_url', 'cover_picture_url'];

    public function getProfilePictureUrlAttribute()
    {
        // If user has a profile picture, return the S3 URL
        if ($this->profile_picture) {
            return Storage::disk('s3')->temporaryUrl($this->profile_picture, now()->addMinutes(5));
        }
        
        // Default image path
        $defaultImagePath = 'images/default.jpg';
        
        // Check if the default image exists in the public directory
        if (file_exists(public_path($defaultImagePath))) {
            return asset($defaultImagePath);
        }
        
        // Fallback to a known path or placeholder
        return asset('images/placeholder.png');
    }

    public function getCoverPictureUrlAttribute()
    {
        if (!$this->cover_picture) {
            return null;
        }
        return Storage::disk('s3')->temporaryUrl($this->cover_picture, now()->addMinutes(5));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}