<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'employment_type',
        'start_date',
        'end_date',
        'current_role',
        'location',
        'location_type',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_role' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}