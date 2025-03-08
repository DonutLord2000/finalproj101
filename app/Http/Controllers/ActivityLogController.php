<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logType = $request->input('log_type', 'all');
        
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');
        
        if ($logType !== 'all') {
            $query->where('log_type', $logType);
        }
        
        $logs = $query->paginate(20);
        
        $logTypes = ActivityLog::distinct('log_type')->pluck('log_type');
        
        return view('activity-logs.index', compact('logs', 'logTypes', 'logType'));
    }
}