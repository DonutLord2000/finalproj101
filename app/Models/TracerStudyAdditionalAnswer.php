<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerStudyAdditionalAnswer extends Model
{
    protected $fillable = ['pending_response_id', 'additional_data'];

    protected $casts = [
        'additional_data' => 'array',
    ];

    public function pendingResponse()
    {
        return $this->belongsTo(PendingResponse::class, 'pending_response_id');
    }
}

