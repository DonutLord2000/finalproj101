<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationDocument extends Model
{
    protected $fillable = [
        'verification_request_id',
        'document_path',
        'original_name',
        'mime_type'
    ];

    public function verificationRequest()
    {
        return $this->belongsTo(VerificationRequest::class);
    }
}