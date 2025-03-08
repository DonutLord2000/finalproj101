<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public function log($logType, $action)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'log_type' => $logType,
            'action' => $action,
        ]);
    }
}