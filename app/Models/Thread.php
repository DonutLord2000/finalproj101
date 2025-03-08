<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function upvotes()
    {
        return $this->reactions()->where('type', 'upvote');
    }

    public function hearts()
    {
        return $this->reactions()->where('type', 'heart');
    }

    public function getUpvotesAttribute() {
        return $this->reactions()->where('type', 'upvote')->count();
    }
    
    public function getHeartsAttribute() {
        return $this->reactions()->where('type', 'heart')->count();
    }
    
}