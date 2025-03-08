<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholarship_application_id',
        'document_path',
        'original_name',
        'mime_type',
    ];

    /**
     * Get the application that owns the document.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(ScholarshipApplication::class, 'scholarship_application_id');
    }
}

