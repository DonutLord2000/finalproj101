<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}