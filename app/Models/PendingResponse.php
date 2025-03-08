<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingResponse extends Model
{
    protected $fillable = ['response_data', 'status'];

    protected $casts = [
        'response_data' => 'array',
    ];

    public function additionalAnswers()
    {
        return $this->hasOne(TracerStudyAdditionalAnswer::class, 'pending_response_id');
    }
}

