<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewsPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'visible_to',
        'source',
        'image',
        'video',
    ];
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        try {
            return Storage::disk('s3')->temporaryUrl($this->image, now()->addMinutes(5));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getVideoUrlAttribute()
    {
        if (!$this->video) {
            return null;
        }
        
        try {
            return Storage::disk('s3')->temporaryUrl($this->video, now()->addMinutes(5));
        } catch (\Exception $e) {
            return null;
        }
    }
}