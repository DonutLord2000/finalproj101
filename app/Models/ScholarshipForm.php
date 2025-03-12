<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'is_active',
        'storage_disk'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}