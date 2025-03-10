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
        if (!$this->profile_picture) {
            return asset('storage/profile-photos/default.png');
        }
        return Storage::disk('s3')->temporaryUrl($this->profile_picture, now()->addMinutes(5));
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